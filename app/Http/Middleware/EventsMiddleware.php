<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\CorsightReads;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post') && $request->is('corsight/eventos')) {
            $data = $request->json()->all();

            // Validação dos dados
            $validator = Validator::make($data, [
                'event_type' => 'required|string',
                'event_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('EventsMiddleware: Validação falhou', ['errors' => $validator->errors()]);
                return response()->json(['error' => $validator->errors()], 400);
            }

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
                Log::error('EventsMiddleware: Erro ao salvar evento: ' . $e->getMessage());
                return response()->json(['error' => 'Erro ao salvar evento'], 500);
            }
        }

        return $next($request);
    }

    private function convertTimestamp($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->toDateTimeString();
    }
}