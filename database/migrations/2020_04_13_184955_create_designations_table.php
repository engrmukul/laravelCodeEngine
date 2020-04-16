<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->bigInteger('designations_id')->autoIncrement('true');
            $table->string('designations_name',100)->nullable(false);
            $table->string('short_name', 20)->nullable(true);
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
        Schema::dropIfExists('designations');
    }
}
