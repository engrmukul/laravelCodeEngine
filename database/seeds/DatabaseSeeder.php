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
            'sys_modules_name' => 'Admin',
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
            'sys_user_levels_name' => 'Admin',
            'description' => 'admin',
            'parent_sys_user_levels_id' => 0,
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
        DB::table('sys_menus')->insert(
            [
                [
                    'sys_menus_name' => 'Home',
                    'menus_description' => 'home menu',
                    'menus_type' => 'Main',
                    'parent_sys_menus_id' => 0,
                    'sys_modules_id' => 1,
                    'icon_class' => 'fa fa-list',
                    'menu_url' => 'home',
                    'sort_number' => 1,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active',
                ],
                [
                    'sys_menus_name' => 'Users',
                    'menus_description' => 'User menu',
                    'menus_type' => 'Main',
                    'parent_sys_menus_id' => 0,
                    'sys_modules_id' => 1,
                    'icon_class' => 'fa fa-list',
                    'menu_url' => 'user-list',
                    'sort_number' => 2,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active',
                ],
                [
                    'sys_menus_name' => 'Users Levels',
                    'menus_description' => 'User Level menu',
                    'menus_type' => 'Main',
                    'parent_sys_menus_id' => 0,
                    'sys_modules_id' => 1,
                    'icon_class' => 'fa fa-list',
                    'menu_url' => 'grid/sys_user_levels',
                    'sort_number' => 3,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active',
                ],
                [
                    'sys_menus_name' => 'Modules',
                    'menus_description' => 'Module menu',
                    'menus_type' => 'Main',
                    'parent_sys_menus_id' => 0,
                    'sys_modules_id' => 1,
                    'icon_class' => 'fa fa-list',
                    'menu_url' => 'grid/sys_modules',
                    'sort_number' => 4,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active',
                ],
                [
                    'sys_menus_name' => 'Menus',
                    'menus_description' => 'Menu menu',
                    'menus_type' => 'Main',
                    'parent_sys_menus_id' => 0,
                    'sys_modules_id' => 1,
                    'icon_class' => 'fa fa-list',
                    'menu_url' => 'grid/sys_menus',
                    'sort_number' => 5,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d'),
                    'status' => 'Active',
                ]
            ]

        );

        //DEFAULT USER LEVELS PRIVILEGE DATA INSERT
        DB::table('sys_privilege_levels')->insert([
            'users_id' => 1,
            'user_levels_id' => 1,
        ]);

        //DEFAULT MENU PRIVILEGE DATA INSERT
        DB::table('sys_privilege_menus')->insert(
            [
                [
                    'menus_id' => 1,
                    'user_levels_id' => 1,
                ],
                [
                    'menus_id' => 2,
                    'user_levels_id' => 1,
                ],
                [
                    'menus_id' => 3,
                    'user_levels_id' => 1,
                ],
                [
                    'menus_id' => 4,
                    'user_levels_id' => 1,
                ],
                [
                    'menus_id' => 5,
                    'user_levels_id' => 1,
                ]
            ]
        );

        //DEFAULT MENU USER PRIVILEGE DATA INSERT
        DB::table('sys_privilege_menu_users')->insert([
            'user_id' => 1,
            'access_menu' => '1,2,3,4,5',
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
                    'sqltext' => 'SELECT sys_user_levels_id,sys_user_levels_name',
                    'sqlsource' => 'FROM sys_user_levels',
                    'value_field' => 'sys_user_levels_id',
                    'option_field' => 'sys_user_levels_name',
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
                    'sqltext' => 'SELECT sys_modules_id,sys_modules_name',
                    'sqlsource' => 'FROM sys_modules',
                    'value_field' => 'sys_modules_id',
                    'option_field' => 'sys_modules_name',
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
