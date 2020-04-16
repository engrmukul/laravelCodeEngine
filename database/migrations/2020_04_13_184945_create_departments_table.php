<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigInteger('departments_id')->autoIncrement('true');
            $table->string('departments_name',100)->nullable(false);
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
        Schema::dropIfExists('departments');
    }
}
