<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMasterEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_master_entry', function (Blueprint $table) {
            $table->bigInteger('sys_master_entry_id')->autoIncrement('true');
            $table->string('sys_master_entry_name',50)->nullable(false);
            $table->string('route_name',50)->nullable(true);
            $table->string('master_entry_title',255)->nullable(true);
            $table->string('sub_form_ids',100)->nullable(true);
            $table->enum('form_action_mode', ['default', 'ajax'])->default('ajax');
            $table->enum('form_save_mode', ['default', 'instant'])->default('default')->nullable(true);
            $table->enum('form_view_mode', ['default', 'modal', 'tab'])->default('default')->nullable(true);
            $table->string('form_action',50)->nullable(true);
            $table->enum('method', ['get', 'post'])->default('post')->nullable(true);
            $table->tinyInteger('form_column',1)->autoIncrement(false)->nullable(true)->default(3);
            $table->string('form_id',50)->nullable(true);
            $table->string('form_class',200)->nullable(true);
            $table->text('form_additional_attr')->nullable(true);
            $table->tinyInteger('form_add_more',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->text('tagged_grid')->nullable(true);
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
        Schema::dropIfExists('sys_master_entry');
    }
}
