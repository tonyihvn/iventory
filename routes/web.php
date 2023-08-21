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

// Welcome and Home Pages
Route::get('/', 'HomeController@index')->name('dashboard')->middleware('role:Admin,Manager,User,Observer,DCTAdmin,DCTManager,DCTUser');

Route::get('dashboard', 'HomeController@index')->name('dashboard')->middleware('role:Admin,Manager,User,Observer,DCTAdmin,DCTManager,DCTUser');

Route::get('/home', 'HomeController@user_dashboard')->name('home')->middleware('auth');

Route::get('/register','RegisterController@showRegistrationForm')->name('register')->middleware('role:Admin,Manager,DCTAdmin,DCTManager');

// Settings
Route::resource('settings', 'SettingsController')->middleware('role:Admin');
Route::get('edit_settings/{id}', 'SettingsController@edit')->name('edit_settings')->middleware('role:Admin');

// Suppliers
Route::resource('suppliers', 'SupplierController')->middleware('role:Admin,DCTAdmin');
Route::get('add_supplier', 'SupplierController@create')->name('add_supplier')->middleware('role:Admin,DCTAdmin');
Route::get('supplies', 'SuppliesController@index')->name('supplies')->middleware('role:Admin,Super');

// Inventories
Route::resource('inventories', 'InventoryController')->middleware('auth');

Route::get('inventory', 'InventoryController@index')->name('inventory')->middleware('auth');
Route::get('inventorycategory/{category}/', 'InventoryController@categoryInventory')->name('inventorycategory')->middleware('auth');
Route::get('user_items/{userid}/', 'InventoryController@userItems')->name('user_items')->middleware('auth');
Route::get('dataquality', 'InventoryController@dataQuality')->name('dataquality')->middleware('role:Admin,Manager');
Route::post('updateInventory','InventoryController@updateInventory')->name('updateInventory')->middleware('role:Admin,Manager');

Route::get('add_item', 'InventoryController@create')->name('add_item')->middleware('auth');
Route::get('item/{id}', 'InventoryController@edit')->name('item')->middleware('auth');
Route::get('print_item/{id}', 'InventoryController@show')->name('print_item')->middleware('auth');
Route::get('reports', 'InventoryController@reports')->name('reports')->middleware('auth');
Route::post('item_search','InventoryController@item_search')->name('item_search')->middleware('auth');
Route::post('fixItems', 'InventoryController@fixItems')->name('fixItems')->middleware('auth');
Route::get('update-tagnumbers', 'InventoryController@updateTagnumbers')->name('update-tagnumbers')->middleware('auth');
Route::post('update-tags', 'InventoryController@updateTags')->name('update-tags')->middleware('auth');


// Item Requests
Route::get('requests', 'InventoryController@requests')->name('requests')->middleware('auth');
Route::get('request/{id}', 'InventoryController@request')->name('request')->middleware('auth');

Route::post('new_request', 'InventoryController@new_request')->name('new_request')->middleware('auth');
Route::post('request_destroy','InventoryController@request_destroy')->name('request_destroy')->middleware('auth');
Route::post('update_request', 'InventoryController@update_request')->name('update_request')->middleware('auth');

// Facilities
Route::resource('facilities', 'FacilitiesController')->middleware('role:Admin,Manager');
Route::get('add_facility', 'FacilitiesController@create')->name('add_facility')->middleware('role:Admin,Manager');
Route::get('facility/{id}', 'FacilitiesController@edit')->middleware('role:Admin,Manager');
Route::get('facilityitems/{fid}', 'InventoryController@facilityItems')->middleware('role:Admin,Manager');

// Movements
Route::resource('movements', 'MovementController')->middleware('role:Admin,Manager');
Route::get('move_item/{id}', 'MovementController@edit')->name('move_item')->middleware('role:Admin,Manager');

// Audits
Route::resource('audits', 'AuditController')->middleware('role:Admin');

// Departments
Route::resource('departments', 'DepartmentController')->middleware('auth');
Route::get('add_department', 'DepartmentController@create')->name('add_department')->middleware('role:Admin,Manager');

// Categories
Route::resource('categories', 'CategoryController')->middleware('auth');

// Units
Route::resource('units', 'UnitController')->middleware('auth');
Route::get('add_unit', 'UnitController@create')->name('add_unit')->middleware('role:Admin,Manager');

// ACCESS AND AUTHENTICATIONS
Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('users', function(){
    return View('users');
})->middleware('role:Admin,Manager,DCTAdmin,DCTManager');

Route::get('edit_user/{id}', 'CategoryController@editUser')->name('edit_user')->middleware('role:Admin,Manager,DCTAdmin,DCTManager');
Route::delete('deleteUser/{id}', 'CategoryController@deleteUser')->name('deleteUser')->middleware('role:Admin');
Route::put('updateUser', 'CategoryController@updateUser')->name('updateUser')->middleware('role:Admin,Manager,DCTAdmin,DCTManager');


// STOCKS

Route::get('uitems', 'InventoryController@uItems')->name('uitems')->middleware('role:Admin,Super');
Route::get('edit_uitem/{uid}', 'InventoryController@editUItems')->name('edit_uitem')->middleware('role:Admin,Super');
Route::get('deleteuitem/{uid}', 'InventoryController@deleteuitem')->name('deleteuitem')->middleware('role:Admin,Super');
Route::post('newuItem', 'InventoryController@newuItem')->name('newuItem')->middleware('role:Admin,Super');

Route::get('add-stock', 'InventoryController@addStock')->name('add-stock')->middleware('role:Admin,Super');
Route::post('newSupply', 'InventoryController@newSupply')->name('newSupply')->middleware('role:Admin,Super');


// DCTOOLS
Route::resource('dctools', 'DctoolsController')->middleware('auth');
Route::get('dctools', 'DctoolsController@index')->name('dctools')->middleware('role:Admin,Manager,DCTAdmin,DCTManager,DCTUser');
Route::get('add-dctool', 'DctoolsController@create')->name('add-dctool')->middleware('role:Admin,Manager,DCTAdmin');
Route::get('add-dcstock/{dcid}', 'DctoolsController@addDCTStock')->name('add-dcstock')->middleware('role:DCTAdmin,Admin,Super');
Route::post('newDCTSupply', 'DctoolsController@newDCTSupply')->name('newDCTSupply')->middleware('role:Admin,Super,DCTAdmin');
Route::get('send-dctools/{dcid}', 'DctoolsController@dcDistribution')->name('send-dctools')->middleware('role:DCTAdmin,Admin,Super,DCTManager,DCTUser');
Route::post('savedcDistribution', 'DctoolsController@savedcDistribution')->name('savedcDistribution')->middleware('role:Admin,Super,DCTAdmin,DCTManager');
Route::get('dctreport/{dcid}', 'DctoolsController@dcReport')->name('dctreport')->middleware('role:DCTAdmin,Admin,Super,DCTManager');
Route::get('dcutilization/{dcid}', 'DctoolsController@dcUtilization')->name('dctutilization')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser');
Route::post('savedcUtilization', 'DctoolsController@savedcUtilization')->name('savedcUtilization')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser');
Route::get('futilization/{dcid}', 'DctoolsController@fdcUtilization')->name('futilization')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser');
Route::get('new-dctreport', 'DctoolsController@newDCTReport')->name('new-dctreport')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser');
Route::post('generateDCTReport', 'DctoolsController@generateDCTReport')->name('generateDCTReport')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser');
Route::post('bulkToolAction', 'DctoolsController@bulkToolAction')->name('bulkToolAction')->middleware('role:Admin,Super,DCTAdmin,DCTManager');
Route::post('saveBulkdcDistribution', 'DctoolsController@saveBulkdcDistribution')->name('saveBulkdcDistribution')->middleware('role:Admin,Super,DCTAdmin,DCTManager');
Route::post('newBulkDCTSupply', 'DctoolsController@newBulkDCTSupply')->name('newBulkDCTSupply')->middleware('role:Admin,Super,DCTAdmin');
Route::get('confirm-delivery', 'DctoolsController@confirmDelivery')->name('confirm-delivery')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser');
Route::post('saveConfirmation', 'DctoolsController@saveConfirmation')->name('saveConfirmation')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser');


// HELP LINK
Route::get('help', function(){
    return View('help');
});
