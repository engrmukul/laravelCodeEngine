<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysPrivilegeMenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_privilege_menu_users', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable(false)->foreignId('user_id')->references('id')->on('sys_users')->default(0);
            $table->text('access_menu')->nullable(true);
            $table->text('exclude_menu')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_privilege_menu_users');
    }
}
