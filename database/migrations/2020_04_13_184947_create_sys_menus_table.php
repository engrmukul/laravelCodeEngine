<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_menus', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement('true');
            $table->string('name',100)->nullable(false);
            $table->text('menus_description')->nullable(true);
            $table->enum('menus_type', ['Main','Sub'])->default('Main')->nullable(false);
            $table->integer('parent_menus_id')->nullable(false)->autoIncrement(false)->foreignId('parent_menus_id')->references('id')->on('sys_menus');
            $table->integer('modules_id')->nullable(false)->autoIncrement(false)->foreignId('modules_id')->references('id')->on('sys_modules');
            $table->string('icon_class',100)->nullable(true);
            $table->string('menu_url',100)->nullable(true);
            $table->tinyInteger('sort_number',3)->autoIncrement(false)->nullable(false);
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
        Schema::dropIfExists('sys_menus');
    }
}
