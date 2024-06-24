<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendToEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_to_email', function (Blueprint $table) {
            $table->id();
            $table->string('module_id')->default(1);
            $table->string('module');
            $table->unsignedBigInteger('user_id');
            $table->string('send_to');
            $table->string('send_cc')->nullable();
            $table->string('send_bcc')->nullable();
            $table->string('page_title');
            $table->string('content_title');
            $table->text('header_description');
            $table->text('content_description');
            $table->string('attach')->nullable();
            $table->string('config_file')->nullable();
            $table->string('status')->default('NOT_SEND');
            $table->text('log')->nullable();
            $table->timestamp('date_modified')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_to_email');
    }
}
