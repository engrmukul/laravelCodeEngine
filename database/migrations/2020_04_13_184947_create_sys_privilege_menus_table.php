<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysPrivilegeMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_privilege_menus', function (Blueprint $table) {
            $table->integer('menus_id')->unsigned()->unique()->nullable(false)->foreignId('menus_id')->references('id')->on('sys_menus');
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
        Schema::dropIfExists('sys_privilege_menus');
    }
}
