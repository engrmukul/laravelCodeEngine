<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysDropdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_dropdowns', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement('true');
            $table->string('dropdown_slug',100)->nullable(true)->unique();
            $table->enum('dropdown_mode', ['dropdown', 'dropdown_grid'])->default('dropdown');
            $table->string('sys_search_panel_slug',100)->nullable(true);
            $table->text('sqltext')->nullable(true);
            $table->text('sqlsource')->nullable(true);
            $table->text('sqlcondition')->nullable(true);
            $table->text('sqlgroupby')->nullable(true);
            $table->text('sqlhaving')->nullable(true);
            $table->text('sqlorderby')->nullable(true);
            $table->bigInteger('sqllimit')->nullable(true);
            $table->string('value_field',50)->nullable(true);
            $table->string('option_field',100)->nullable(true);
            $table->tinyInteger('multiple',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->text('search_columns')->nullable(true);
            $table->string('dropdown_name',100)->nullable(false)->default(0);
            $table->text('description')->nullable(false);
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
        Schema::dropIfExists('sys_dropdowns');
    }
}
