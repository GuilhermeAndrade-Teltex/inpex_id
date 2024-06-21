<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_permissions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_modified')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('menu1_id')->nullable();
            $table->unsignedBigInteger('menu2_id')->nullable();
            $table->unsignedBigInteger('menu3_id')->nullable();
            $table->boolean('create')->default(false);
            $table->boolean('show')->default(false);
            $table->boolean('edit')->default(false);
            $table->boolean('destroy')->default(false);
            $table->boolean('export')->default(false);
            $table->boolean('access_log')->default(false);
            $table->boolean('audit_log')->default(false);

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('users_roles')->onDelete('cascade');
            $table->foreign('menu1_id')->references('id')->on('menus1')->onDelete('cascade');
            $table->foreign('menu2_id')->references('id')->on('menus2')->onDelete('cascade');
            $table->foreign('menu3_id')->references('id')->on('menus3')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_permissions');
    }
}