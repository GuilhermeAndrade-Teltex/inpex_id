<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module', 255);
            $table->integer('module_id');
            $table->string('status', 50);
            $table->timestamp('date_created')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
            $table->dateTime('date_modified')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('order')->nullable();
            $table->string('source', 10)->nullable();
            $table->string('name_cropped', 255)->nullable();
            $table->string('path_cropped', 255)->nullable();
            $table->unsignedInteger('width_cropped')->nullable();
            $table->unsignedInteger('height_cropped')->nullable();
            $table->string('name_original', 255)->nullable();
            $table->string('path_original', 255)->nullable();
            $table->unsignedInteger('width_original')->nullable();
            $table->unsignedInteger('height_original')->nullable();
            $table->string('name_thumbs', 255)->nullable();
            $table->string('path_thumbs', 255)->nullable();
            $table->integer('width_thumbs')->nullable();
            $table->integer('height_thumbs')->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('html_attributions', 255)->nullable();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
