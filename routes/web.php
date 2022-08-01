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
Route::get('/', 'HomeController@index')->name('dashboard')->middleware('role:Admin');

Route::get('dashboard', 'HomeController@index')->name('dashboard')->middleware('role:Admin');

Route::get('/home', 'HomeController@user_dashboard')->name('home')->middleware('auth');


// Settings
Route::resource('settings', 'SettingsController');
Route::get('edit_settings/{id}', 'SettingsController@edit')->name('edit_settings');

// Suppliers
Route::resource('suppliers', 'SupplierController');
Route::get('add_supplier', 'SupplierController@create')->name('add_supplier');

// Inventories
Route::resource('inventories', 'InventoryController')->middleware('auth');
Route::get('inventorycategory/{category}/', 'InventoryController@categoryInventory')->middleware('auth');

Route::get('add_item', 'InventoryController@create')->name('add_item');
Route::get('item/{id}', 'InventoryController@edit');
Route::get('print_item/{id}', 'InventoryController@show')->name('print_item');
Route::get('reports', 'InventoryController@reports')->name('reports');
Route::post('product_search','InventoryController@product_search')->name('product_search');

// Item Requests
Route::get('requests', 'InventoryController@requests')->name('requests');
Route::get('request/{id}', 'InventoryController@request')->name('request');

Route::post('new_request', 'InventoryController@new_request')->name('new_request');
Route::post('request_destroy','InventoryController@request_destroy')->name('request_destroy');
Route::post('update_request', 'InventoryController@update_request')->name('update_request');

// Facilities
Route::resource('facilities', 'FacilitiesController');
Route::get('add_facility', 'FacilitiesController@create')->name('add_facility');
Route::get('facility/{id}', 'FacilitiesController@edit');

// Movements
Route::resource('movements', 'MovementController');
Route::get('move_item/{id}', 'MovementController@edit')->name('move_item');

// Audits
Route::resource('audits', 'AuditController');

// Departments
Route::resource('departments', 'DepartmentController');
Route::get('add_department', 'DepartmentController@create')->name('add_department');

// Categories
Route::resource('categories', 'CategoryController');


// Units
Route::resource('units', 'UnitController');
Route::get('add_unit', 'UnitController@create')->name('add_unit');

// ACCESS AND AUTHENTICATIONS
Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('users', function(){
    return View('users');
});

Route::get('edit_user/{id}', function(){
    return View('edit_user');
});
Route::get('edit_user/{id}', 'CategoryController@editUser')->name('edit_user');
Route::post('deleteUser', 'CategoryController@deleteUser')->name('deleteUser');
Route::put('updateUser', 'CategoryController@updateUser')->name('updateUser');

// HELP LINK
Route::get('help', function(){
    return View('help');
});