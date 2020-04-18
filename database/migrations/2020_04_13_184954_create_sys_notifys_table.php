<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysNotifysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_notifys', function (Blueprint $table) {
            $table->bigInteger('sys_notifys_id')->autoIncrement('true');
            $table->enum('generated_from', ['System','Person'])->default('System')->nullable(false);
            $table->string('generated_source',50)->nullable(false);
            $table->bigInteger('notify_to')->foreignId('notify_to')->references('id')->on('sys_users')->default(0);
            $table->string('event_for',20)->nullable(true);
            $table->string('event_id',255)->nullable(true);
            $table->text('text')->nullable(true);
            $table->string('url_ref',100)->nullable(true);
            $table->dateTime('created_at')->nullable(true);
            $table->enum('priority', ['5','4','3','2','1'])->default('3')->nullable(false);
            $table->enum('seen_status', ['Unseen','Seen'])->default('Unseen');
            $table->dateTime('seen_at')->nullable(true);
            $table->tinyInteger('mailed',1)->autoIncrement(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_notifys');
    }
}
