<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorsightReads extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'event_id',
        'msg_send_timestamp',
        'camera_id',
        'stream_id',
        'camera_description',
        'node_id',
        'analysis_mode',
        'camera_notes',
        'record_frames',
        'utc_time_recorded',
        'utc_time_zone',
        'frame_id',
        'frame_width',
        'frame_height',
        'bounding_box',
        'appearance_id',
        'utc_time_started',
        'first_frame_id',
        'fs_store',
        'fs_update',
        'crop_frame_id',
        'crop_frame_timestamp',
        'norm',
        'detector_score',
        'face_frame_pts',
        'crop_bbox',
        'face_score',
        'pitch',
        'yaw',
        'masked_score',
        'face_crop_img',
        'poi_display_name',
        'watchlists',
        'poi_notes',
        'utc_time_matched',
        'poi_id',
        'poi_confidence',
        'age_group_outcome',
        'gender_outcome',
        'liveness_outcome',
        'mask_outcome',
        'updates',
        'trigger',
        'signature',
        'privacy_profile_id',
    ];

    protected $casts = [
        'camera_notes' => 'array',
        'bounding_box' => 'array',
        'crop_bbox' => 'array',
        'watchlists' => 'array',
        'poi_notes' => 'array',
        'updates' => 'array',
    ];
}
