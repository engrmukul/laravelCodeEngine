<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysPrivilegeModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_privilege_modules', function (Blueprint $table) {
            $table->bigInteger('users_id')->nullable(false)->foreignId('users_id')->references('id')->on('sys_users')->default(0);
            $table->integer('modules_id')->nullable(false)->foreignId('modules_id')->references('id')->on('sys_modules');
            $table->integer('user_levels_id')->nullable(true)->foreignId('user_levels_id')->references('id')->on('sys_user_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_privilege_modules');
    }
}
