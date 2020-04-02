<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMasterEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_master_entry_details', function (Blueprint $table) {
            $table->bigInteger('sys_master_entry_details_id')->autoIncrement('true');
            $table->string('sys_master_entry_name',50)->nullable(false);
            $table->string('table_name',255)->nullable(false);
            $table->string('field_name',50)->nullable(false);
            $table->string('label_name',50)->nullable(false);
            $table->string('label_class',100)->nullable(false)->default('form-label');
            $table->string('placeholder',100)->nullable(false);
            $table->enum('input_type', ['dropdown','text','textarea','email','date','datetime','checkbox','radio','button','number','submit'])->nullable(false)->default('text');
            $table->string('sorting',3)->nullable(false);
            $table->string('input_id',50)->nullable(false);
            $table->string('input_class',100)->nullable(false)->default('form-control');
            $table->string('validator_function',100)->nullable(true);
            $table->text('validate_expression')->nullable(true);
            $table->tinyInteger('disabled',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->tinyInteger('required',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->string('dropdown_slug',50)->nullable(true)->default(NULL);
            $table->text('dropdown_options')->nullable(true);
            $table->enum('dropdown_view', ['combo','grid','autocomplete'])->nullable(false)->default('combo');
            $table->text('autocomplete_query')->nullable(true);
            $table->bigInteger('created_by')->foreignId('user_id')->references('id')->on('users')->default(0);
            $table->bigInteger('updated_by')->foreignId('user_id')->references('id')->on('users')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
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
        Schema::dropIfExists('sys_master_entry_details');
    }
}
