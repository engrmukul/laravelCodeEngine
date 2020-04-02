<?php

namespace App\Console\Commands;

use App\Helpers\ApsysHelper;
use App\Models\Dropdown;
use Illuminate\Console\Command;
use App\User;
use DB;
use URL;
use Auth;

class MakeTable extends Command
{
    protected $signature = 'apsis:tableMaker{module}';
    protected $description = 'Console based module maker';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(){
        $moduleName = $this->argument('module');
        if ($this->confirm('Is ' . $moduleName . ' correct, do you wish to continue? [y|N]')) {
            $table_name = $moduleName;
            $sql = "CREATE TABLE `$table_name` (
                `".$table_name."_id`  int(10) NOT NULL AUTO_INCREMENT ,
                `".$table_name."_name`  varchar(150) NOT NULL ,
                `description`  text NOT NULL ,
                `created_by`  int(10) NULL ,
                `created_at`  datetime NULL ,
                `updated_by`  int(10) NULL ,
                `updated_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `status`  enum('Active','Inactive') NULL DEFAULT 'Active' ,
                PRIMARY KEY (`".$table_name."_id`)
            )";
            DB::select(DB::raw($sql));
            echo 'Valo hoise';
        } else {
            $this->info("Thanks try again!");
        }
    }
}
