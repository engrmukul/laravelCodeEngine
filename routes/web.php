<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
//Auth::routes(['register' => false]);

/*=============================================HOME====================================================*/
Route::get('/home', 'HomeController@index')->name('home');

/*================================= USER AUTHENTICATION ==========================================*/
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Auth::routes();
Route::post('multi-login-action', 'User\UserController@multiLoginAction');
Route::post('notify-dismiss', 'User\UserController@notifyDismiss');

/*=============================================MASTER ENTRY====================================================*/
Route::get('grid/{gridname}', 'Master\MasterGridController@getGrid')->name('grid');
Route::post('get-grid-data', 'Master\MasterGridController@getGridData')->name('get-grid-data');
Route::post('delete-record', 'Master\MasterGridController@deleteRecord');
Route::get('form/{formname}', 'Master\MasterFormController@buildMasterForm')->name('form');
Route::get('entryform/{formname}/{table_name?}/{primary_key_field?}/{id?}', 'Master\MasterFormController@buildFormForEntry')->name('entryform');
Route::post('masterFormDataStore', 'Master\MasterFormController@masterFormDataStore')->name('masterFormDataStore');
Route::get('get-autocomplete-query/{mode?}/{master_det_id?}/{id?}', 'Master\MasterFormController@getAutocompleteQuery');

/*=================================== USER MANAGEMENT =========================================*/
Route::resource('users', 'User\UserController');
Route::post('getUserRaw', 'User\UserController@getUserRaw')->name('getUserRaw');
Route::post('profile', 'User\UserController@getUserProfile')->name('get-user-profile');
Route::match(['get', 'post'], 'user-list', 'User\UserController@List')->name('user-list');
Route::get('user-entry/{id?}', 'User\UserController@entryForm')->name('user-entry');
Route::match(['get', 'post'], 'store-user-info', 'User\UserController@storeUser')->name('store-user-info');

/*================================ MENU MANAGER ========================================*/
Route::get('menu_list', 'MenuManagement@menu_list')->name('menu_list');
Route::get('menulist', 'MenuManagement@menuList')->name('menu_list');
Route::post('menu_entry', 'MenuManagement@menu_entry')->name('menu_entry');
Route::post('getMenuRaw', 'MenuManagement@getMenuRaw')->name('getMenuRaw');
Route::post('saveMenuOrder', 'MenuManagement@saveMenuOrder')->name('saveMenuOrder');
Route::post('menuDelete', 'MenuManagement@menuDelete')->name('menuDelete');
Route::get('get_menu_for_level', 'MenuManagement@get_menu_for_level')->name('get_menu_for_level');
