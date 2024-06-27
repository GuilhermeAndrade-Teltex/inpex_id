<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorsightReadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corsight_reads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type');
            $table->char('event_id', 36);
            $table->timestamp('msg_send_timestamp')->nullable();
            $table->char('camera_id', 36);
            $table->char('stream_id', 36)->nullable();
            $table->string('camera_description')->nullable();
            $table->char('node_id', 36)->nullable();
            $table->string('analysis_mode')->nullable();
            $table->json('camera_notes')->nullable();
            $table->boolean('record_frames');
            $table->timestamp('utc_time_recorded')->nullable();
            $table->integer('utc_time_zone');
            $table->integer('frame_id');
            $table->integer('frame_width');
            $table->integer('frame_height');
            $table->json('bounding_box');
            $table->char('appearance_id', 36);
            $table->timestamp('utc_time_started')->nullable();
            $table->integer('first_frame_id');
            $table->boolean('fs_store');
            $table->boolean('fs_update');
            $table->integer('crop_frame_id');
            $table->timestamp('crop_frame_timestamp')->nullable();
            $table->float('norm');
            $table->float('detector_score');
            $table->bigInteger('face_frame_pts');
            $table->json('crop_bbox');
            $table->float('face_score');
            $table->float('pitch');
            $table->float('yaw');
            $table->float('masked_score')->nullable();
            $table->longText('face_crop_img')->nullable();
            $table->string('poi_display_name')->nullable();
            $table->json('watchlists');
            $table->json('poi_notes')->nullable();
            $table->timestamp('utc_time_matched')->nullable();
            $table->string('poi_id')->nullable();
            $table->float('poi_confidence')->nullable();
            $table->string('age_group_outcome')->nullable();
            $table->string('gender_outcome')->nullable();
            $table->string('liveness_outcome')->nullable();
            $table->string('mask_outcome')->nullable();
            $table->json('updates');
            $table->integer('trigger');
            $table->string('signature')->nullable();
            $table->char('privacy_profile_id', 36);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corsight_reads');
    }
}