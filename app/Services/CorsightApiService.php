<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Config as AppConfig;
use App\Models\CorsightReads;

class CorsightApiService
{
    protected $baseUri;
    protected $username;
    protected $password;
    protected $token;
    protected $tokenExpiration;
    protected $httpOptions = [
        'headers' => [
            'Accept' => 'application/json',
            'User-Agent' => 'spider',
        ],
        'allow_redirects' => [
            'max' => 10,
        ],
        'timeout' => 300,
        'curl' => [
            CURLOPT_BUFFERSIZE => 16384,
        ],
        'connect_timeout' => 180,
        'verify' => false,
    ];

    const LOGIN_URL = ':5004/auth/login';
    const WATCHLISTS_URL = ':8080/poi_db/watchlist';
    const CLEAR_POI_DB_URL = ':8080/poi_db';
    const POI_URL = ':8080/poi_db/poi';
    const EVENTS_URL = ':5005/events/';
    const DETECT_FACE_URL = ':8080/poi_db/face/detect';
    const ANALYZE_FACE_URL = ':8080/poi_db/face/analyze';

    public function __construct()
    {
        $this->baseUri = Config::get('services.corsight_api.base_uri');
        $this->username = Config::get('services.corsight_api.username');
        $this->password = Config::get('services.corsight_api.password');
    }

    public function authenticate()
    {
        $url = "{$this->baseUri}" . self::LOGIN_URL;
        try {
            $response = Http::asForm()
                ->withOptions($this->httpOptions)
                ->post($url, [
                    'username' => $this->username,
                    'password' => $this->password,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['token'])) {
                    $config = AppConfig::firstOrNew(['id' => 1]);
                    $config->corsight_api_token = $data['data']['token'];
                    $config->corsight_api_token_expiration = now()->addSeconds($data['expires_in'])->timestamp;
                    $config->save();

                    return $data['data']['token'];
                } else {
                    Log::error('Corsight API authentication failed - Invalid response format', [
                        'response_body' => $response->body(),
                    ]);

                    throw new \Exception('Authentication failed - Invalid response format');
                }
            } else {
                Log::error('Corsight API authentication failed', [
                    'response_body' => $response->body(),
                    'response_status' => $response->status(),
                ]);

                throw new \Exception('Authentication failed');
            }
        } catch (\Exception $e) {
            Log::error('Connection failed', ['exception' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getToken()
    {
        $config = AppConfig::first();

        if ($config && $config->corsight_api_token && $config->corsight_api_token_expiration > now()->timestamp) {
            return $config->corsight_api_token;
        }

        return $this->authenticate();
    }

    public function getResource()
    {
        try {
            $token = $this->getToken();
            $response = Http::withToken($token)->withOptions($this->httpOptions)->get("{$this->baseUri}/resource");

            Log::debug('Corsight API response:', ['response' => $response]);

            if ($response->successful()) {
                Log::info('Corsight API request successful', ['response' => $response->json()]);
                return $response->json();
            } else {
                throw new \Exception('Corsight API request failed: ' . $response->body(), $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching Corsight API resource:', ['exception' => $e]);
            throw $e;
        }
    }

    public function createResource(array $data)
    {
        $token = $this->getToken();

        if (!$token) {
            return null;
        }

        $response = Http::withToken($token)->withOptions($this->httpOptions)->post("{$this->baseUri}/resource", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Failed to create resource: ' . $response->body());
        return null;
    }

    public function listWatchlists()
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->withOptions($this->httpOptions)
            ->get("{$this->baseUri}" . self::WATCHLISTS_URL);

        if ($response->status() === 401) {
            $token = $this->authenticate();
            $response = Http::withToken($token)->withOptions($this->httpOptions)
                ->get("{$this->baseUri}" . self::WATCHLISTS_URL);
        }

        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error('Failed to list watchlists', [
                'response_body' => $response->body(),
                'response_status' => $response->status(),
            ]);
            return null;
        }
    }

    public function addWatchlist(array $data): ?Response
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->withOptions($this->httpOptions)
            ->post("{$this->baseUri}" . self::WATCHLISTS_URL, $data);

        if ($response->status() === 401) {
            // Se a resposta for 401, tente autenticar novamente e repetir a requisição
            $token = $this->authenticate();
            $response = Http::withToken($token)->withOptions($this->httpOptions)
                ->post("{$this->baseUri}" . self::WATCHLISTS_URL, $data);
        }

        return $response;
    }

    public function detectFace(): array
    {
        try {
            $token = $this->getToken();

            if (!$token) {
                throw new \Exception('Failed to get Corsight API token');
            }

            $img1 = 'http://intranet.teltexcorp.com:8090/images/photo-profile/2-thales-guilherme-rollo.jpg';
            $img2 = 'http://intranet.teltexcorp.com:8090/images/photo-card/2-thales-guilherme-rollo.jpg';

            $img1Base64 = base64_encode(file_get_contents($img1));
            $img2Base64 = base64_encode(file_get_contents($img2));

            $data = [
                'imgs' => [
                    ['img' => $img1Base64],
                    ['img' => $img2Base64],
                ],
            ];

            $response = Http::withToken($token)->withOptions($this->httpOptions)
                ->post("{$this->baseUri}" . self::DETECT_FACE_URL, $data);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data']['detections'])) {
                    $detections = $responseData['data']['detections'];
                    $results = [];

                    foreach ($detections as $detection) {
                        if ($detection) {
                            foreach ($detection as $face) {
                                if (isset($face['img'])) {
                                    $analysisResult = $this->analyzeFace($face['img']);
                                    if ($analysisResult) {
                                        $results[] = $analysisResult;
                                    }
                                }
                            }
                        }
                    }

                    return $results;
                }
            }

            throw new \Exception('Failed to detect faces', $response->status());
        } catch (\Exception $e) {
            Log::error('Error detecting faces:', ['exception' => $e]);
            return [];
        }
    }

    public function analyzeFace(string $face): array
    {
        try {
            $token = $this->getToken();

            $data = [
                'image_payload' => ['img' => $face],
            ];

            $response = Http::withToken($token)->withOptions($this->httpOptions)
                ->post("{$this->baseUri}" . self::ANALYZE_FACE_URL, $data);

            if ($response->successful()) {
                $responseData = $response->json();

                if (
                    isset($responseData['data']['valid_face']) &&
                    $responseData['data']['valid_face']
                ) {
                    return $this->addPerson($face);
                } else {
                    Log::info('Invalid face detected', ['response' => $responseData]);
                    return [];
                }
            } else {
                throw new \Exception('Corsight API analyze face request failed', $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error analyzing face:', ['exception' => $e]);
            return [];
        }
    }

    public function addPerson(array $data)
    {
        try {
            $token = $this->getToken();
            $response = Http::withToken($token)->withOptions($this->httpOptions)->post("{$this->baseUri}" . self::POI_URL, $data);

            Log::info('Received response from Corsight API', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                return $response->json();
            } else {
                $body = $response->body();
                Log::error('Corsight API add person request failed', ['status' => $response->status(), 'body' => $body]);
                throw new \Exception('Corsight API add person request failed');
            }
        } catch (\Exception $e) {
            Log::error('Error adding person:', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'data' => $data]);
            return null;
        }
    }

    public function addFaces(array $data): array
    {
        try {
            $token = $this->getToken();

            if (!isset($data['id'])) {
                throw new \InvalidArgumentException('Missing "id" field in data');
            }

            $response = Http::withToken($token)->withOptions($this->httpOptions)
                ->post("{$this->baseUri}" . self::POI_URL . "/{$data['id']}/add_faces", $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                $body = $response->body();
                Log::error('Corsight API add person request failed', ['status' => $response->status(), 'body' => $body]);
                throw new \Exception('Corsight API add faces request failed', $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error adding faces:', ['exception' => $e]);
            return [];
        }
    }

    public function clearPoiDb(): bool
    {
        try {
            $token = $this->getToken();
            $response = Http::withToken($token)->withOptions($this->httpOptions)->delete("{$this->baseUri}" . self::CLEAR_POI_DB_URL);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error clearing POI DB:', ['exception' => $e]);
            return false;
        }
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function getEvents(callable $callback)
    {
        while (true) {
            $ch = curl_init("{$this->baseUri}" . self::EVENTS_URL);

            Log::info('Conectando à API Corsight em: ' . "{$this->baseUri}" . self::EVENTS_URL);
            Log::info('Token de autenticação: ' . $this->getToken());

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: text/event-stream',
                'Cache-Control: no-cache',
                'Authorization: Bearer ' . $this->getToken(),
            ]);

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($callback) {
                static $buffer = '';
                $buffer .= $data;

                $lines = explode("\n", $buffer);

                foreach ($lines as $i => $line) {
                    $line = trim($line);
                    if (empty($line)) {
                        continue;
                    }

                    if (strpos($line, 'data:') === 0) {
                        // $eventData = json_decode(substr($line, 5), true);
                        $jsonString = substr($line, 5);
                        $eventData = json_decode($jsonString, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            // if ($eventData['event_type'] == 'appearance' && isset($eventData['appearance_data']['poi_id']) && $eventData['appearance_data']['poi_id'] !== NULL) {
                            if ($eventData['event_type'] == 'appearance') {
                                Log::info('Evento decodificado (appearance):', $eventData);

                                $imagePath = $this->saveImage($eventData['crop_data']['face_crop_img'], $eventData['appearance_data']['poi_id']);

                                try {
                                    $corsightReadData = [
                                        'event_type' => $eventData['event_type'],
                                        'event_id' => $eventData['event_id'],
                                        'msg_send_timestamp' => $eventData['msg_send_timestamp'],
                                        'camera_id' => $eventData['camera_data']['camera_id'],
                                        'stream_id' => $eventData['camera_data']['stream_id'],
                                        'camera_description' => $eventData['camera_data']['camera_description'],
                                        'node_id' => $eventData['camera_data']['node_id'],
                                        'analysis_mode' => $eventData['camera_data']['analysis_mode'],
                                        'camera_notes' => json_encode($eventData['camera_data']['camera_notes']),
                                        'record_frames' => $eventData['camera_data']['record_frames'],
                                        'utc_time_recorded' => $eventData['frame_data']['utc_time_recorded'],
                                        'utc_time_zone' => $eventData['frame_data']['utc_time_zone'],
                                        'frame_id' => $eventData['frame_data']['frame_id'],
                                        'frame_width' => $eventData['frame_data']['frame_width'],
                                        'frame_height' => $eventData['frame_data']['frame_height'],
                                        'bounding_box' => json_encode($eventData['frame_data']['bounding_box']),
                                        'appearance_id' => $eventData['appearance_data']['appearance_id'],
                                        'utc_time_started' => $eventData['appearance_data']['utc_time_started'],
                                        'first_frame_id' => $eventData['appearance_data']['first_frame_id'],
                                        'fs_store' => $eventData['appearance_data']['fs_store'],
                                        'fs_update' => $eventData['appearance_data']['fs_update'],
                                        'crop_data_frame_id' => $eventData['crop_data']['frame_id'],
                                        'frame_timestamp' => $eventData['crop_data']['frame_timestamp'],
                                        'norm' => $eventData['crop_data']['norm'],
                                        'detector_score' => $eventData['crop_data']['detector_score'],
                                        'face_frame_pts' => $eventData['crop_data']['face_frame_pts'],
                                        'bbox' => json_encode($eventData['crop_data']['bbox']),
                                        'face_score' => $eventData['crop_data']['face_score'],
                                        'pitch' => $eventData['crop_data']['pitch'],
                                        'yaw' => $eventData['crop_data']['yaw'],
                                        'face_crop_img' => $imagePath,
                                        'masked_score' => json_encode($eventData['crop_data']['masked_score']),
                                        'watchlists' => json_encode($eventData['match_data']['watchlists']),
                                        'utc_time_matched' => $eventData['match_data']['utc_time_matched'],
                                        'poi_id' => array_key_exists('poi_id', $eventData['match_data']) ? $eventData['match_data']['poi_id'] : null,
                                        'poi_confidence' => $eventData['match_data']['poi_confidence'],
                                        'face_features_data' => json_encode($eventData['face_features_data']),
                                        'updates' => json_encode($eventData['updates']),
                                        'trigger' => $eventData['trigger'],
                                        'signature' => $eventData['signature'],
                                        'privacy_profile_id' => $eventData['privacy_profile_id'],
                                    ];

                                    try {
                                        CorsightReads::create($corsightReadData);
                                    } catch (\Illuminate\Database\QueryException $e) {
                                        Log::error('Erro de SQL ao salvar os dados do evento:', ['exception' => $e, 'query' => $e->getSql(), 'bindings' => $e->getBindings()]);
                                    } catch (\Exception $e) {
                                        Log::error('Erro geral ao salvar os dados do evento:', ['exception' => $e]);
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Erro ao salvar os dados do evento:', ['exception' => $e]);
                                }

                                $callback($eventData);
                            }
                        } else {
                            Log::error('Erro ao decodificar JSON: ' . json_last_error_msg() . ', JSON: ' . $jsonString);
                        }
                    }

                    unset($lines[$i]);
                }

                $buffer = implode("\n", $lines);
                return strlen($data);
            });

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $result = curl_exec($ch);

            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            Log::info('URL final após redirecionamento: ' . $finalUrl);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            Log::info('Código de status HTTP: ' . $httpCode);

            if ($httpCode === 401) {
                Log::warning('Falha na autenticação. Tentando renovar o token...');

                $newToken = $this->authenticate();

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: text/event-stream',
                    'Cache-Control: no-cache',
                    'Authorization: Bearer ' . $newToken,
                ]);

                $result = curl_exec($ch);

                if ($result === false && curl_errno($ch) === CURLE_PARTIAL_FILE) {
                    Log::warning('Conexão fechada inesperadamente. Tentando reconectar em 5 segundos...');
                    sleep(5);
                    continue;
                }
            }

            curl_close($ch);
            break;
        }
    }

    private function saveImage($base64Image, $appearanceId)
    {
        try {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            $filename = $appearanceId . '.jpg';

            Storage::disk('public')->put('corsight_images/' . $filename, $imageData);

            return 'corsight_images/' . $filename;
        } catch (\Exception $e) {
            Log::error('Erro ao salvar a imagem:', ['exception' => $e]);
            return null;
        }
    }
}