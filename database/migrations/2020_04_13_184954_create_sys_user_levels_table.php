<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_user_levels', function (Blueprint $table) {
            $table->integer('sys_user_levels_id')->autoIncrement('true')->unsigned();
            $table->string('sys_user_levels_name',50)->nullable(false)->unique();
            $table->string('description',100)->nullable(true);
            $table->integer('parent_sys_user_levels_id',4)->autoIncrement(false)->foreignId('parent_sys_user_levels_id')->references('sys_user_levels_id')->on('sys_user_levels')->default(0);
            $table->integer('min_username_length',2)->autoIncrement(false)->nullable(true)->default(8);
            $table->integer('max_username_length',2)->autoIncrement(false)->nullable(true)->default(25);
            $table->tinyInteger('multi_login_allow',1)->autoIncrement(false)->nullable(false)->default(0);
            $table->integer('max_wrong_login_attemp',1)->autoIncrement(false)->nullable(false)->default(3);
            $table->enum('wrong_login_attemp', ['No Restriction','Blocked','Block for a Period'])->default('No Restriction');
            $table->integer('block_period',4)->autoIncrement(false)->nullable(true)->default(30);
            $table->integer('session_time_out',3)->autoIncrement(false)->nullable(false)->default(30);
            $table->string('password_regEx',255)->nullable(true);
            $table->string('password_regEx_error_msg',255)->nullable(true);
            $table->integer('password_expiry_notify',3)->autoIncrement(false)->nullable(false)->default(15);
            $table->integer('password_expiry_duration',3)->autoIncrement(false)->nullable(false)->default(90);
            $table->enum('password_expiry_action', ['Notify','Force'])->default('Notify')->nullable(true);
            $table->bigInteger('created_by')->foreignId('user_id')->references('id')->on('sys_users')->default(0);
            $table->bigInteger('updated_by')->foreignId('user_id')->references('id')->on('sys_users')->default(0);
            $table->enum('status', ['Active','Inactive'])->default('Active');
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
        Schema::dropIfExists('sys_user_levels');
    }
}
