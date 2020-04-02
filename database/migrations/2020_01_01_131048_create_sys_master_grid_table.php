<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMasterGridTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_master_grid', function (Blueprint $table) {
            $table->bigInteger('sys_master_grid_id')->autoIncrement('true');
            $table->string('sys_master_grid_name',50)->nullable(false);
            $table->string('sys_master_entry_name',50)->nullable(true);
            $table->string('master_entry_url',100)->nullable(true);
            $table->string('grid_title',100)->nullable(true);
            $table->text('grid_sql');
            $table->text('sqlsource');
            $table->text('sqlcondition')->nullable(true);
            $table->text('sqlgroupby')->nullable(true);
            $table->text('sqlhaving')->nullable(true);
            $table->text('sqlorderby')->nullable(true);
            $table->text('sqllimit')->nullable(true);
            $table->string('action_table',50)->nullable(true);
            $table->string('primary_key_field',100)->nullable(true);
            $table->string('search_panel_slug',100)->nullable(true);
            $table->string('hide_col_position',100)->nullable(true)->default(1);
            $table->text('search_columns')->nullable(true);
            $table->text('tr_data_attr')->nullable(true);
            $table->tinyInteger('enable_form',1)->autoIncrement(false)->nullable(true)->default(1);
            $table->string('additional_grid',100)->nullable(true);
            $table->tinyInteger('export_excel',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->tinyInteger('export_pdf',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->tinyInteger('export_csv',1)->autoIncrement(false)->nullable(true)->default(0);
            $table->tinyInteger('enable_printing',1)->autoIncrement(false)->nullable(true)->default(0);
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
        Schema::dropIfExists('sys_master_grid');
    }
}
