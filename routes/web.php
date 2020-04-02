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

Auth::routes();
//Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/customers', 'PartyController@customerList')->name('customers');
Route::get('/customer', 'PartyController@customerAdd')->name('customer');

Route::get('/vendors', 'PartyController@vendorList')->name('vendors');
Route::get('/vendor', 'PartyController@vendorAdd')->name('vendor');

Route::get('/orders', 'OrderController@orderList')->name('orders');

Route::get('/delivery', 'DeliveryController@deliveryList')->name('delivery');
Route::get('/new-delivery', 'DeliveryController@deliveryAdd')->name('new-delivery');

Route::get('/payments', 'PaymentController@paymentList')->name('payments');

Route::get('/expenses', 'ExpenseController@expenseList')->name('expenses');

Route::get('/services', 'ServiceController@serviceList')->name('services');
Route::get('/new-service', 'ServiceController@serviceAdd')->name('new-service');



/*=============================================MASTER ENTRY====================================================*/
Route::get('grid/{gridname}', 'Master\MasterGridController@getGrid')->name('grid');
Route::post('get-grid-data', 'Master\MasterGridController@getGridData')->name('get-grid-data');
Route::post('delete-record', 'Master\MasterGridController@deleteRecord');
Route::get('form/{formname}', 'Master\MasterFormController@buildMasterForm')->name('form');
Route::get('entryform/{formname}/{table_name?}/{primary_key_field?}/{id?}', 'Master\MasterFormController@buildFormForEntry')->name('entryform');
Route::post('masterFormDataStore', 'Master\MasterFormController@masterFormDataStore')->name('masterFormDataStore');
Route::get('get-autocomplete-query/{mode?}/{master_det_id?}/{id?}', 'Master\MasterFormController@getAutocompleteQuery');
