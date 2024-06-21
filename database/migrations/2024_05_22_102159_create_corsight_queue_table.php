<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('corsight_queue', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('module_id');
            $table->string('module');
            $table->json('data');
            $table->string('endpoint');
            $table->longText('log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corsight_queue');
    }
};
