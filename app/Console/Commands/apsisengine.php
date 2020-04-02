<?php

namespace App\Console\Commands;

use App\Helpers\ApsysHelper;
use App\Models\Dropdown;
use Illuminate\Console\Command;
use App\User;
use DB;
use URL;
use Auth;

class apsisengine extends Command
{
    protected $signature = 'make:module{module}';
    protected $description = 'Console based module maker';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(){
        $moduleName = $this->argument('module');

        if ($this->confirm('Is ' . $moduleName . ' correct, do you wish to continue? [y|N]')) {
            $controllerDir = 'app/Http/Controllers/';
            $modelDir = 'app/Models/';
            $viewDir = 'resources/views/';
            /*=============================================Start Controller================================================*/
            /*            $ctrl_stp1 = explode('_', $moduleName);
            $ctrl_stp2 = '';
            foreach ($ctrl_stp1 as $ctrl_stp){
                $ctrl_stp2 .= ucfirst($ctrl_stp);
            }
            $controller_name = $ctrl_stp2 . 'Controller' . '.php';
            $controllerFolder = $controllerDir . ucfirst($ctrl_stp2);
            if (!is_dir($controllerFolder)) {
                mkdir($controllerFolder, 0777, true);
            } else {
                $files = glob($controllerFolder . '/*');
                foreach ($files as $file) {
                    if (is_file($file)){
                        unlink($file);
                    }
                }
            }
            $controllerPage = fopen($controllerFolder.'/'. $controller_name, 'w') or die('Cannot open file:  Controller');
            $controllerContent = file_get_contents($controllerDir . 'Master/TemplateController.php');
            fwrite($controllerPage, $controllerContent);
            file_put_contents($controllerFolder.'/' . $controller_name, str_replace('[module]', ucfirst($moduleName), file_get_contents($controllerFolder.'/'
                . $controller_name)));*/
            /*=============================================Start Model================================================*/
            /*            $modelFolder = $modelDir . ucfirst($moduleName);
            if (!is_dir($modelFolder)) {
                mkdir($modelFolder, 0777, true);
            } else {
                $files = glob($modelFolder . '/*');
                foreach ($files as $file) {
                    if (is_file($file)){
                        unlink($file);
                    }
                }
            }
            $modelPage = fopen($modelFolder .'/'. ucfirst($moduleName) . '.php', 'w') or die('Cannot open file:  model');
            $modelContent = file_get_contents($modelDir . 'Master/TemplateModel.php');
            fwrite($modelPage, $modelContent);
            file_put_contents($modelFolder. '/' . ucfirst($moduleName) . '.php', str_replace('[module]', $moduleName, file_get_contents($modelFolder. '/' . ucfirst($moduleName) . '.php')));*/
            /*=============================================Start View================================================*/
            /*            $viewFolder = $viewDir . ucfirst($moduleName);
            if (!is_dir($viewFolder)) {
                mkdir($viewFolder, 0777, true);
            }*/
            /*=============================================End View================================================*/
            /*============================================= DB RECORD START ================================================*/
            $fields = DB::select('DESCRIBE ' . $moduleName);
            $masterEntryDetails = array();
            foreach ($fields as $key => $field) {
                if(!in_array($field->Field,['created_by','created_at','updated_by','updated_at']) && $field->Key != 'PRI') {
                    $input_type = 'text';
                    $dropdown_options = NULL;
                    $dropdown_slug = NULL;
                    $masterEntryDetail = array();
                    $masterEntryDetail['sys_master_entry_name'] = $moduleName;
                    $masterEntryDetail['table_name'] = $moduleName;
                    $masterEntryDetail['field_name'] = $field->Field;
                    $masterEntryDetail['input_class'] = 'form-control';
                    $masterEntryDetail['input_id'] = $field->Field;
                    $masterEntryDetail['label_name'] = ucwords(str_replace('_', ' ', $field->Field));
                    $masterEntryDetail['placeholder'] = "Enter " . ucwords(str_replace('_', ' ', $field->Field));

                    $field_types = explode('(', $field->Type);
                    $field_type = $field_types[0];

                    if (in_array($field_type, ['varchar'])) {
                        $input_type = "text";
                    } else if (in_array($field_type, ['text', 'blob', 'longtext'])) {
                        $input_type = "textarea";
                    } else if (in_array($field_type, ['timestamp', 'date', 'datetime'])) {
                        $input_type = "date";
                    } else if (in_array($field_type, ['tinyint'])) {
                        $input_type = "checkbox";
                    } else if (in_array($field_type, ['int']) && $field->Key != 'PRI') {
                        $field_arr = explode('_', $field->Field);
                        if (end($field_arr) == 'id') {
                            $input_type = "dropdown";
                            $dropdown_key = rtrim($field->Field, '_id');
                            $dropdown_slug_info = Dropdown::where('dropdown_slug', '=', $dropdown_key)->first();
                            if (!empty($dropdown_slug_info)) {
                                $dropdown_slug = $dropdown_slug_info->dropdown_slug;
                            } else {
                                $dropdown_insert_arr = array(
                                    'dropdown_slug' => $dropdown_key,
                                    'sqltext' => "SELECT " . $dropdown_key . "_id, " . $dropdown_key . "_name FROM " . $dropdown_key . " WHERE `status` = 'Active'",
                                    'value_field' => $dropdown_key . "_id",
                                    'option_field' => $dropdown_key . "_name",
                                    'multiple' => 0,
                                    'dropdown_name' => $dropdown_key . "_id",
                                    'description' => '',
                                    'status' => 'Active'
                                );
                                DB::table('sys_dropdowns')->insert($dropdown_insert_arr);
                                $dropdown_slug = $dropdown_key;
                            }
                        } else {
                            $input_type = "text";
                        }
                    } else if (in_array($field_type, ['enum'])) {
                        $enum_vals = rtrim($field_types[1], '\')');
                        $enum_val = explode("','", ltrim($enum_vals, '\''));
                        $input_type = "dropdown";
                        $dropdown_options = implode(',', $enum_val);
                    } else {
                        $input_type = "text";
                    }

                    $masterEntryDetail['sorting'] = $key + 1;
                    $masterEntryDetail['input_type'] = $input_type;
                    $masterEntryDetail['dropdown_options'] = $dropdown_options;
                    $masterEntryDetail['dropdown_slug'] = $dropdown_slug;
                    $masterEntryDetails[] = $masterEntryDetail;
                }
            }
            $master_form_button['sys_master_entry_name'] = $moduleName;
            $master_form_button['table_name'] = $moduleName;
            $master_form_button['field_name'] = $moduleName . "_id";
            $master_form_button['label_name'] = 'Submit Form';
            $master_form_button['placeholder'] = 'Submit Form';
            $master_form_button['input_class'] = 'btn btn-primary';
            $master_form_button['input_id'] = '';
            $master_form_button['sorting'] = 100;
            $master_form_button['input_type'] = 'submit';
            $master_form_button['dropdown_options'] = NULL;
            $master_form_button['dropdown_slug'] = NULL;

            $masterEntryDetails[] = $master_form_button;
            $master_entry['sys_master_entry_name'] = $moduleName;
            $master_entry['master_entry_title'] = ucfirst(str_replace('_', ' ', $moduleName));
            $master_entry['route_name'] = $moduleName;
            $master_entry['form_id'] = $moduleName;
            $master_entry['form_class'] = 'validator';

            $master_grid['grid_title'] = ucfirst(str_replace('_', ' ', $moduleName));
            $master_grid['grid_sql'] = "SELECT *";
            $master_grid['sqlsource'] = "FROM $moduleName";
            $master_grid['primary_key_field'] = $moduleName . '_id';
            $master_grid['action_table'] = $moduleName;
            $master_grid['sys_master_grid_name'] = $moduleName;
            $master_grid['sys_master_entry_name'] = $moduleName;
            $master_grid['master_entry_url'] = $moduleName;

            DB::table('sys_master_entry')->where('sys_master_entry_name', '=', $moduleName)->delete();
            DB::table('sys_master_entry')->insert($master_entry);

            DB::table('sys_master_entry_details')->where('sys_master_entry_name', '=', $moduleName)->delete();
            DB::table('sys_master_entry_details')->insert($masterEntryDetails);
//            dd(DB::getQueryLog());
            DB::table('sys_master_grid')->where('sys_master_grid_name', '=', $moduleName)->delete();
            DB::table('sys_master_grid')->insert($master_grid);
            echo $this->info("Congratulations!..") . "\n";
            echo $this->info('Your ' . ucfirst($this->argument('module')) . ' module  developed. Browse ' . URL::to('HOSTNAME/grid/' . $this->argument('module')) . "\n");

        } else {
            $this->info("Thanks try again!");
        }
    }
}
