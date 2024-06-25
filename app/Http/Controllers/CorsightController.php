<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\CorsightApiService;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\CorsightReads;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;

class CorsightController extends Controller
{
    protected $corsightApiService;
    protected $validationService;
    protected $breadcrumbService;
    protected $latestAppearanceData = [];

    public function __construct(ValidationService $validationService, BreadcrumbService $breadcrumbService, CorsightApiService $corsightApiService)
    {
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
        $this->corsightApiService = $corsightApiService;
    }

    public function handleEvent(Request $request)
    {
        // Log para verificar a entrada dos dados (opcional)
        Log::info('Received event: ', $request->all());

        // Processar os dados recebidos
        $data = $request->all();

        // Validação dos dados
        $validator = Validator::make($data, [
            'event_type' => 'required|string',
            'event_id' => 'required|string',
        ]);

        try {
            // Converter timestamps
            $msgSendTimestamp = $this->convertTimestamp($data['msg_send_timestamp']);
            $utcTimeRecorded = $this->convertTimestamp($data['frame_data']['utc_time_recorded']);
            $utcTimeStarted = $this->convertTimestamp($data['appearance_data']['utc_time_started']);
            $cropFrameTimestamp = $this->convertTimestamp($data['crop_data']['frame_timestamp']);
            $utcTimeMatched = isset($data['match_data']['utc_time_matched']) ? $this->convertTimestamp($data['match_data']['utc_time_matched']) : null;

            // Verificar se existe um evento recente para o mesmo poi_id
            $existingEvent = CorsightReads::where('poi_id', $data['match_data']['poi_id'])
                ->where('created_at', '>=', now()->subSeconds(10))
                ->orderBy('created_at', 'desc')
                ->first();

            // Salvar a imagem base64 no Storage
            $faceCropImgPath = null;
            if (!empty($data['crop_data']['face_crop_img'])) {
                $faceCropImg = $data['crop_data']['face_crop_img'];
                $image = str_replace('data:image/jpeg;base64,', '', $faceCropImg);
                $image = str_replace(' ', '+', $image);
                $imageName = 'corsight_images/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imageName, base64_decode($image));
                $faceCropImgPath = $imageName;
            }

            if ($existingEvent) {
                // Se existir, atualizar o evento existente com a nova imagem e dados
                $existingEvent->update([
                    'event_type' => $data['event_type'],
                    'event_id' => $data['event_id'],
                    'msg_send_timestamp' => $msgSendTimestamp,
                    'camera_id' => $data['camera_data']['camera_id'],
                    'stream_id' => $data['camera_data']['stream_id'] ?? null,
                    'camera_description' => $data['camera_data']['camera_description'] ?? null,
                    'node_id' => $data['camera_data']['node_id'] ?? null,
                    'analysis_mode' => $data['camera_data']['analysis_mode'] ?? null,
                    'camera_notes' => $data['camera_data']['camera_notes'] ?? null,
                    'record_frames' => $data['camera_data']['record_frames'],
                    'utc_time_recorded' => $utcTimeRecorded,
                    'utc_time_zone' => $data['frame_data']['utc_time_zone'],
                    'frame_id' => $data['frame_data']['frame_id'],
                    'frame_width' => $data['frame_data']['frame_width'],
                    'frame_height' => $data['frame_data']['frame_height'],
                    'bounding_box' => $data['frame_data']['bounding_box'] ?? null,
                    'appearance_id' => $data['appearance_data']['appearance_id'],
                    'utc_time_started' => $utcTimeStarted,
                    'first_frame_id' => $data['appearance_data']['first_frame_id'],
                    'fs_store' => $data['appearance_data']['fs_store'],
                    'fs_update' => $data['appearance_data']['fs_update'],
                    'crop_frame_id' => $data['crop_data']['frame_id'],
                    'crop_frame_timestamp' => $cropFrameTimestamp,
                    'norm' => $data['crop_data']['norm'],
                    'detector_score' => $data['crop_data']['detector_score'],
                    'face_frame_pts' => $data['crop_data']['face_frame_pts'],
                    'crop_bbox' => $data['crop_data']['bbox'] ?? null,
                    'face_score' => $data['crop_data']['face_score'],
                    'pitch' => $data['crop_data']['pitch'],
                    'yaw' => $data['crop_data']['yaw'],
                    'masked_score' => $data['crop_data']['masked_score'] ?? null,
                    'face_crop_img' => $faceCropImgPath,
                    'poi_display_name' => $data['match_data']['poi_display_name'] ?? null,
                    'watchlists' => $data['match_data']['watchlists'] ?? null,
                    'poi_notes' => $data['match_data']['poi_notes'] ?? null,
                    'utc_time_matched' => $utcTimeMatched,
                    'poi_id' => $data['match_data']['poi_id'] ?? null,
                    'poi_confidence' => $data['match_data']['poi_confidence'] ?? null,
                    'age_group_outcome' => $data['face_features_data']['age_group_outcome'] ?? null,
                    'gender_outcome' => $data['face_features_data']['gender_outcome'] ?? null,
                    'liveness_outcome' => $data['face_features_data']['liveness_outcome'] ?? null,
                    'mask_outcome' => $data['face_features_data']['mask_outcome'] ?? null,
                    'updates' => $data['updates'] ?? null,
                    'trigger' => $data['trigger'],
                    'signature' => $data['signature'] ?? null,
                    'privacy_profile_id' => $data['privacy_profile_id'],
                ]);
            } else {
                // Se não existir, criar um novo evento
                CorsightReads::create([
                    'event_type' => $data['event_type'],
                    'event_id' => $data['event_id'],
                    'msg_send_timestamp' => $msgSendTimestamp,
                    'camera_id' => $data['camera_data']['camera_id'],
                    'stream_id' => $data['camera_data']['stream_id'] ?? null,
                    'camera_description' => $data['camera_data']['camera_description'] ?? null,
                    'node_id' => $data['camera_data']['node_id'] ?? null,
                    'analysis_mode' => $data['camera_data']['analysis_mode'] ?? null,
                    'camera_notes' => $data['camera_data']['camera_notes'] ?? null,
                    'record_frames' => $data['camera_data']['record_frames'],
                    'utc_time_recorded' => $utcTimeRecorded,
                    'utc_time_zone' => $data['frame_data']['utc_time_zone'],
                    'frame_id' => $data['frame_data']['frame_id'],
                    'frame_width' => $data['frame_data']['frame_width'],
                    'frame_height' => $data['frame_data']['frame_height'],
                    'bounding_box' => $data['frame_data']['bounding_box'] ?? null,
                    'appearance_id' => $data['appearance_data']['appearance_id'],
                    'utc_time_started' => $utcTimeStarted,
                    'first_frame_id' => $data['appearance_data']['first_frame_id'],
                    'fs_store' => $data['appearance_data']['fs_store'],
                    'fs_update' => $data['appearance_data']['fs_update'],
                    'crop_frame_id' => $data['crop_data']['frame_id'],
                    'crop_frame_timestamp' => $cropFrameTimestamp,
                    'norm' => $data['crop_data']['norm'],
                    'detector_score' => $data['crop_data']['detector_score'],
                    'face_frame_pts' => $data['crop_data']['face_frame_pts'],
                    'crop_bbox' => $data['crop_data']['bbox'] ?? null,
                    'face_score' => $data['crop_data']['face_score'],
                    'pitch' => $data['crop_data']['pitch'],
                    'yaw' => $data['crop_data']['yaw'],
                    'masked_score' => $data['crop_data']['masked_score'] ?? null,
                    'face_crop_img' => $faceCropImgPath,
                    'poi_display_name' => $data['match_data']['poi_display_name'] ?? null,
                    'watchlists' => $data['match_data']['watchlists'] ?? null,
                    'poi_notes' => $data['match_data']['poi_notes'] ?? null,
                    'utc_time_matched' => $utcTimeMatched,
                    'poi_id' => $data['match_data']['poi_id'] ?? null,
                    'poi_confidence' => $data['match_data']['poi_confidence'] ?? null,
                    'age_group_outcome' => $data['face_features_data']['age_group_outcome'] ?? null,
                    'gender_outcome' => $data['face_features_data']['gender_outcome'] ?? null,
                    'liveness_outcome' => $data['face_features_data']['liveness_outcome'] ?? null,
                    'mask_outcome' => $data['face_features_data']['mask_outcome'] ?? null,
                    'updates' => $data['updates'] ?? null,
                    'trigger' => $data['trigger'],
                    'signature' => $data['signature'] ?? null,
                    'privacy_profile_id' => $data['privacy_profile_id'],
                ]);
            }

            return response()->json(['message' => 'Evento recebido com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar evento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao salvar evento'], 500);
        }
    }

    public function listFaces()
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Pessoas' => 'corsight.faces',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Pessoas';

        // Limitar a quantidade de faces para melhorar a performance
        $faces = CorsightReads::orderBy('created_at', 'desc')->take(50)->get();

        return view('pages.corsight.face-list', compact('faces', 'breadcrumbs', 'pageTitle'));
    }

    public function listWatchlist()
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Watchlists' => 'corsight.watchlist',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Watchlists';

        $data_watchlists = $this->corsightApiService->listWatchlists();

        return view('pages.corsight.watchlists-list', compact('data_watchlists', 'breadcrumbs', 'pageTitle'));
    }

    public function addWatchlist(Request $request)
    {
        try {
            $data = $request->validate([
                'watchlist_type' => 'required|string',
                'display_name' => 'required|string',
                'display_color' => 'required|string',
                'watchlist_notes' => 'required|array',
            ]);

            $response = $this->corsightApiService->addWatchlist($data);

            if ($response && $response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Failed to create watchlist'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFacesData()
    {
        // Limitar a quantidade de faces retornadas
        $faces = CorsightReads::orderBy('id', 'desc')->take(50)->get();
        return response()->json($faces);
    }

    public function getPowerBIData()
    {
        $events = CorsightReads::orderBy('created_at', 'desc')->get();

        $data = $events->map(function ($event) {
            // Get related school and student
            $school = School::where('watchlist_id', $event->watchlists[0]['watchlist_id'])->first();
            $client = $school ? $school->client : null;
            $student = Student::where('cpf', $event->poi_id)->first();

            return [
                'client' => $client ? $client->name : null,
                'watchlist' => $school ? $school->name : null,
                'camera_id' => $event->camera_id,
                'camera_description' => $event->camera_description,
                'poi_id' => $event->poi_id,
                'poi_display_name' => $student ? $student->name : $event->poi_display_name,
                'class' => $student ? $student->class : null,
                'utc_time_recorded' => $event->updated_at,
                'gender_outcome' => $student ? $student->gender : null,
                'age_group_outcome' => $student ? $this->getAgeGroup($student->date_of_birth) : null,
            ];
        });

        return response()->json($data);
    }

    private function getAgeGroup($dateOfBirth)
    {
        $age = Carbon::parse($dateOfBirth)->age;

        if ($age < 10) {
            return '0-9';
        } elseif ($age < 20) {
            return '10-19';
        } elseif ($age < 30) {
            return '20-29';
        } elseif ($age < 40) {
            return '30-39';
        } elseif ($age < 50) {
            return '40-49';
        } elseif ($age < 60) {
            return '50-59';
        } elseif ($age < 70) {
            return '60-69';
        } else {
            return '70+';
        }
    }

    private function convertTimestamp($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->toDateTimeString();
    }
}
