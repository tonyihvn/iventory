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
Route::get('/', 'HomeController@index')->name('dashboard')->middleware('role:Super,Admin,Manager,Facility,User,Observer,DCTAdmin,DCTManager,DCTUser');

Route::get('/concurrency', 'HomeController@concurrency')->name('concurrency')->middleware('role:Super,Admin,Manager,Facility,User,Observer');
Route::post('/concurrencyUpdate', 'HomeController@concurrencyUpdate')->name('concurrencyUpdate')->middleware('role:Super,Admin,Manager,Facility,User,Observer');

Route::get('/concurrencies', 'ConcurrencyController@index')->name('concurrencies.index')->middleware('role:Super,Admin,Manager,Facility,User,Observer');
Route::get('/concurrencies/export', 'ConcurrencyController@export')->name('concurrencies.export')->middleware('role:Super,Admin,Manager,Facility,User,Observer');
Route::post('/concurrencies/import', 'ConcurrencyController@import')->name('concurrencies.import')->middleware('role:Super,Admin,Manager,Facility,User,Observer');

Route::get('dashboard', 'HomeController@index')->name('dashboard')->middleware('role:Super,Admin,Manager,Facility,User,Observer,DCTAdmin,DCTManager,DCTUser');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/register','RegisterController@showRegistrationForm')->name('register')->middleware('role:Super,Admin,Manager,DCTAdmin,DCTManager');

// Settings
Route::resource('settings', 'SettingsController')->middleware('role:Super,Admin');
Route::get('edit_settings/{id}', 'SettingsController@edit')->name('edit_settings')->middleware('role:Super,Admin');

// Suppliers
Route::resource('suppliers', 'SupplierController')->middleware('role:Admin,DCTAdmin');
Route::get('add_supplier', 'SupplierController@create')->name('add_supplier')->middleware('role:Super,Admin,DCTAdmin');
Route::get('supplies', 'SuppliesController@index')->name('supplies')->middleware('role:Super,Admin,Super');

// Inventories
Route::resource('inventories', 'InventoryController')->middleware('role:Super,Admin,Manager,Facility');

Route::get('inventory', 'InventoryController@index')->name('inventory')->middleware('role:Super,Admin');
Route::get('inventorycategory/{category}/', 'InventoryController@categoryInventory')->name('inventorycategory')->middleware('auth')->where('category', '.*');
Route::get('user_items/{userid}/', 'InventoryController@userItems')->name('user_items')->middleware('auth');
Route::get('dataquality', 'InventoryController@dataQuality')->name('dataquality')->middleware('role:Super,Admin,Manager');
Route::post('updateInventory','InventoryController@updateInventory')->name('updateInventory')->middleware('role:Super,Admin,Manager');
Route::get('state-inventory/{state}/', 'InventoryController@stateInventory')->name('state-inventory')->middleware('role:Super,Admin,Observer')->where('state', '.*');


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
Route::get('add_facility', 'FacilitiesController@create')->name('add_facility')->middleware('role:Super,Admin,Manager');
Route::get('facility/{id}', 'FacilitiesController@edit')->middleware('role:Admin,Manager');
Route::get('facilityitems/{fid}', 'InventoryController@facilityItems')->middleware('role:Super,Admin,Manager');
Route::get('/get-facilities/{state}', 'InventoryController@getFacilitiesByState')->middleware('role:Super,Admin,Manager');

// State Facilities
Route::get('/state-facilities/{state}', 'StateFacilitiesController@index')->name('state-facilities')->middleware('role:Admin,Manager');
Route::get('/state-facilities-data/{state}', 'StateFacilitiesController@getFacilitiesData');
// Movements
Route::resource('movements', 'MovementController')->middleware('role:Super,Admin,Manager');
Route::get('move_item/{id}', 'MovementController@edit')->name('move_item')->middleware('role:Super,Admin,Manager');

// Audits
Route::resource('audits', 'AuditController')->middleware('role:Super,Admin');

// Departments
Route::resource('departments', 'DepartmentController')->middleware('auth');
Route::get('add_department', 'DepartmentController@create')->name('add_department')->middleware('role:Super,Admin,Manager');

// Categories
Route::resource('categories', 'CategoryController')->middleware('auth');

// Units
Route::resource('units', 'UnitController')->middleware('auth');
Route::get('add_unit', 'UnitController@create')->name('add_unit')->middleware('role:Super,Admin,Manager');

// ACCESS AND AUTHENTICATIONS
Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('users', function(){
    return View('users');
})->name('users')->middleware('role:Super,Admin,Manager,DCTAdmin,DCTManager');

Route::get('edit_user/{id}', 'CategoryController@editUser')->name('edit_user')->middleware('role:Super,Admin,Manager,DCTAdmin,DCTManager');
Route::delete('deleteUser/{id}', 'CategoryController@deleteUser')->name('deleteUser')->middleware('role:Super,Admin');
Route::put('updateUser', 'CategoryController@updateUser')->name('updateUser')->middleware('role:Super,Admin,Manager,DCTAdmin,DCTManager');
Route::post('addMoreFacilities', 'InventoryController@addMoreFacilities')->name('addMoreFacilities')->middleware('role:Admin,Super,DCTAdmin,DCTManager');
Route::post('switchFacility', 'InventoryController@switchFacility')->name('switchFacility')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser');

// STOCKS

Route::get('uitems', 'InventoryController@uItems')->name('uitems')->middleware('role:Admin,Super');
Route::get('uitem-category/{itemid}', 'InventoryController@uitemInventory')->name('uitem-category')->middleware('role:Admin,Super');

Route::get('edit_uitem/{uid}', 'InventoryController@editUItems')->name('edit_uitem')->middleware('role:Admin,Super');
Route::get('deleteuitem/{uid}', 'InventoryController@deleteuitem')->name('deleteuitem')->middleware('role:Admin,Super');
Route::post('newuItem', 'InventoryController@newuItem')->name('newuItem')->middleware('role:Admin,Super');

Route::get('add-stock', 'InventoryController@addStock')->name('add-stock')->middleware('role:Admin,Super');
Route::post('newSupply', 'InventoryController@newSupply')->name('newSupply')->middleware('role:Admin,Super');


// DCTOOLS
Route::resource('dctools', 'DctoolsController')->middleware('auth');
Route::get('dctools', 'DctoolsController@index')->name('dctools')->middleware('role:Super,Admin,Manager,DCTAdmin,DCTManager,DCTUser,Manager');
Route::get('add-dctool', 'DctoolsController@create')->name('add-dctool')->middleware('role:Super,Admin,Manager,DCTAdmin,Manager');
Route::get('add-dcstock/{dcid}', 'DctoolsController@addDCTStock')->name('add-dcstock')->middleware('role:DCTAdmin,Admin,Super,Manager');
Route::post('newDCTSupply', 'DctoolsController@newDCTSupply')->name('newDCTSupply')->middleware('role:Admin,Super,DCTAdmin,Manager');
Route::get('send-dctools/{dcid}', 'DctoolsController@dcDistribution')->name('send-dctools')->middleware('role:DCTAdmin,Admin,Super,DCTManager,DCTUser,Manager');
Route::post('savedcDistribution', 'DctoolsController@savedcDistribution')->name('savedcDistribution')->middleware('role:Admin,Super,DCTAdmin,DCTManager, Manager');
Route::get('dctreport/{dcid}', 'DctoolsController@dcReport')->name('dctreport')->middleware('role:DCTAdmin,Admin,Super,DCTManager,Manager');
Route::get('dcutilization/{dcid}', 'DctoolsController@dcUtilization')->name('dctutilization')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser,Manager');
Route::post('savedcUtilization', 'DctoolsController@savedcUtilization')->name('savedcUtilization')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser,Manager');
Route::get('futilization/{dcid}', 'DctoolsController@fdcUtilization')->name('futilization')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser,Manager');
Route::get('new-dctreport', 'DctoolsController@newDCTReport')->name('new-dctreport')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser,Manager');
Route::get('distribution-report', 'DctoolsController@DCTDistributionReport')->name('distribution-report')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser,Manager');
Route::post('generateDCTReport', 'DctoolsController@generateDCTReport')->name('generateDCTReport')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser,Manager');
Route::post('bulkToolAction', 'DctoolsController@bulkToolAction')->name('bulkToolAction')->middleware('role:Admin,Super,DCTAdmin,DCTManager, Manager');
Route::post('saveBulkdcDistribution', 'DctoolsController@saveBulkdcDistribution')->name('saveBulkdcDistribution')->middleware('role:Admin,Super,DCTAdmin,DCTManager,Manager');
Route::post('newBulkDCTSupply', 'DctoolsController@newBulkDCTSupply')->name('newBulkDCTSupply')->middleware('role:Admin,Super,DCTAdmin');
Route::get('confirm-delivery', 'DctoolsController@confirmDelivery')->name('confirm-delivery')->middleware('role:DCTAdmin,DCTManager,Admin,Super,DCTUser,Manager');
Route::post('saveConfirmation', 'DctoolsController@saveConfirmation')->name('saveConfirmation')->middleware('role:Admin,Super,DCTAdmin,DCTManager,DCTUser,Manager');
Route::get('del-dct/{tid}', 'DctoolsController@DeleteDCTool')->name('del-dct')->middleware('role:DCTAdmin,DCTManager,Admin,Super,Manager');


// HELP LINK
Route::get('help', function(){
    return View('help');
});
