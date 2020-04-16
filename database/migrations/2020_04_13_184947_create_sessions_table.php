<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement('true');
            $table->bigInteger('user_id')->unsigned()->foreignId('user_id')->references('id')->on('sys_users');
            $table->string('ip_address',45)->nullable(true);
            $table->text('user_agent')->nullable(true);
            $table->text('payload')->nullable(false);
            $table->integer('last_activity')->autoIncrement(false)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
