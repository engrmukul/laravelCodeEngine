<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //DEFAULT MODULE DATA INSERT
        DB::table('sys_modules')->insert([
            'name' => 'Admin',
            'modules_icon' => 'fa fa-list',
            'style_class' => '',
            'module_lang' => 'admin',
            'description' => 'for admin',
            'home_url' => 'home',
            'created_by' => 1,
            'created_at' => date('Y-m-d'),
            'status' => 'Active',
        ]);

        //DEFAULT USER LEVELS DATA INSERT
        DB::table('sys_user_levels')->insert([
            'name' => 'Admin',
            'description' => 'admin',
            'parent_level_id' => 0,
            'min_username_length' => '8',
            'max_username_length' => '25',
            'multi_login_allow' => '0',
            'max_wrong_login_attemp' => '3',
            'wrong_login_attemp' => 'No Restriction',
            'block_period' => '30',
            'session_time_out' => '30',
            'password_regEx' => '',
            'password_regEx_error_msg' => '',
            'password_expiry_notify' => '15',
            'password_expiry_duration' => '90',
            'password_expiry_action' => 'Notify',
            'created_by' => 1,
            'created_at' => date('Y-m-d'),
            'status' => 'Active',
        ]);

        //DEFAULT MENU DATA INSERT
        DB::table('sys_menus')->insert([
            'name' => 'home',
            'menus_description' => 'home menu',
            'menus_type' => 'Main',
            'parent_menus_id' => 0,
            'modules_id' => 1,
            'icon_class' => 'fa fa-list',
            'menu_url' => 'home',
            'sort_number' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d'),
            'status' => 'Active',
        ]);

        //DEFAULT USER LEVELS PRIVILEGE DATA INSERT
        DB::table('sys_privilege_levels')->insert([
            'users_id' => 1,
            'user_levels_id' => 1,
        ]);

        //DEFAULT MENU PRIVILEGE DATA INSERT
        DB::table('sys_privilege_menus')->insert([
            'menus_id' => 1,
            'user_levels_id' => 1,
        ]);

        //DEFAULT MENU USER PRIVILEGE DATA INSERT
        DB::table('sys_privilege_menu_users')->insert([
            'user_id' => 1,
            'access_menu' => 1,
            'exclude_menu' => '',
        ]);

        //DEFAULT MODULE PRIVILEGE DATA INSERT
        DB::table('sys_privilege_modules')->insert([
            'users_id' => 1,
            'modules_id' => 1,
            'user_levels_id' => 1,
        ]);

        //DEFAULT DROPDOWN DATA INSERT
        DB::table('sys_dropdowns')->insert(
            [
                [
                    'dropdown_slug' => 'user_levels',
                    'dropdown_mode' => 'dropdown',
                    'sys_search_panel_slug' => '',
                    'sqltext' => 'SELECT id,name',
                    'sqlsource' => 'FROM sys_user_levels',
                    'value_field' => 'id',
                    'option_field' => 'name',
                    'multiple' => 1,
                    'dropdown_name' => 'user_levels[]',
                    'description' => 'NA',
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active'
                ],
                 [
                    'dropdown_slug' => 'modules',
                    'dropdown_mode' => 'dropdown',
                    'sys_search_panel_slug' => '',
                    'sqltext' => 'SELECT id,name',
                    'sqlsource' => 'FROM sys_modules',
                    'value_field' => 'id',
                    'option_field' => 'name',
                    'multiple' => 1,
                    'dropdown_name' => 'default_module_id',
                    'description' => 'NA',
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active'
                ]
            ]
        );

        //DEFAULT USER DATA INSERT
        DB::table('sys_users')->insert([
            'name' => 'Mr Jhon',
            'email' => 'jhon@hotmail.com',
            'username' => 'jhon@hotmail.com',
            'user_code' => '20200410001',
            'default_url' => 'home',
            'default_module_id' => 1,
            'password'=>Hash::make(123456),
            'created_by' => 1,
            'created_at' => date('Y-m-d'),
            'status' => 'Active'
        ]);

    }
}
