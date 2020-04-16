<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_users', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement('true');
            $table->string('user_code',20)->nullable(true)->unique();
            $table->string('username',150)->nullable(true);
            $table->string('email',100)->nullable(true)->unique();
            $table->string('password',100)->nullable(false);
            $table->string('password_key',50)->nullable(true);
            $table->tinyInteger('is_employee',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->string('name',100)->nullable(true);
            $table->string('spouse_name',100)->nullable(true);
            $table->timestamp('email_verified_at')->nullable(true);
            $table->string('mobile',20)->nullable(true);
            $table->date('date_of_birth')->nullable(true);
            $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-',''])->default('');
            $table->enum('gender', ['Female','Male','Common'])->default('Male');
            $table->enum('religion', ['Buddhist','Christian','Hindu','Islam','Others'])->default('Islam');
            $table->enum('marital_status', ['Married','Unmarried','Devorced','Widow','Single'])->default('Unmarried');
            $table->string('father_name',100)->nullable(true);
            $table->string('mother_name',100)->nullable(true);
            $table->string('nationality',20)->nullable(true);
            $table->string('nid',20)->nullable(true);
            $table->string('tin',20)->nullable(true);
            $table->string('passport',100)->nullable(true);
            $table->string('user_image',100)->nullable(true)->default('/img/users/Avatar.png');
            $table->string('user_sign',100)->nullable(true);
            $table->string('present_address_line',250)->nullable(true);
            $table->string('present_district',50)->nullable(true);
            $table->string('present_thana',50)->nullable(true);
            $table->string('present_po',50)->nullable(true);
            $table->string('present_post_code',20)->nullable(true);
            $table->string('present_village',100)->nullable(true);
            $table->text('address')->nullable(true);
            $table->string('permanent_address_line',250)->nullable(true);
            $table->string('permanent_district',50)->nullable(true);
            $table->string('permanent_thana',50)->nullable(true);
            $table->string('permanent_po',50)->nullable(true);
            $table->string('permanent_post_code',20)->nullable(true);
            $table->string('permanent_village',100)->nullable(true);
            $table->dateTime('last_login')->nullable(true);
            $table->date('date_of_join')->nullable(true);
            $table->date('date_of_confirmation')->nullable(true);
            $table->string('default_url',255)->nullable(true);
            $table->integer('default_module_id',3)->autoIncrement(false)->nullable(true)->default(0);
            $table->integer('designations_id',10)->autoIncrement(false)->nullable(true);
            $table->integer('departments_id',10)->autoIncrement(false)->nullable(true);
            $table->integer('branchs_id',10)->autoIncrement(false)->nullable(true)->default(1);
            $table->string('remember_token',100)->nullable(true);
            $table->tinyInteger('is_reliever',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->integer('reliever_to',10)->autoIncrement(false)->nullable(true);
            $table->dateTime('reliever_start_datetime')->nullable(true);
            $table->dateTime('reliever_end_datetime')->nullable(true);
            $table->timestamp('password_changed_date')->nullable(true);
            $table->integer('wrong_attempts_count', 10)->autoIncrement(false)->nullable(true)->default(0);
            $table->enum('working_type', ['Full Time','Part Time','Contractual','On Call'])->default('Full Time');
            $table->bigInteger('created_by')->foreignId('user_id')->references('id')->on('sys_users')->default(0);
            $table->bigInteger('updated_by')->foreignId('user_id')->references('id')->on('sys_users')->default(0);
            $table->enum('status', ['Active','Inactive','Resignation','Termination','Absconding','Retirement'])->default('Active');
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
        Schema::dropIfExists('sys_users');
    }
}
