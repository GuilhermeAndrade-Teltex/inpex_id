<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenus3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus3', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_modified')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->unsignedBigInteger('menus2_id');
            $table->string('name');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('dashboard')->default(false);
            $table->string('method')->nullable();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('menus2_id')->references('id')->on('menus2')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus3');
    }
}