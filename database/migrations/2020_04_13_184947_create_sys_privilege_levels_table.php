<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysPrivilegeLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_privilege_levels', function (Blueprint $table) {
            $table->integer('users_id')->unsigned()->unique()->nullable(true)->foreignId('users_id')->references('id')->on('sys_users');
            $table->integer('user_levels_id')->nullable(false)->foreignId('user_levels_id')->references('id')->on('sys_user_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_privilege_levels');
    }
}
