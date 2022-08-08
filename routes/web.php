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

Route::get('/register','RegisterController@showRegistrationForm')->name('register')->middleware('role:Admin');

// Settings
Route::resource('settings', 'SettingsController')->middleware('role:Admin');
Route::get('edit_settings/{id}', 'SettingsController@edit')->name('edit_settings')->middleware('role:Admin');

// Suppliers
Route::resource('suppliers', 'SupplierController')->middleware('role:Admin');
Route::get('add_supplier', 'SupplierController@create')->name('add_supplier')->middleware('role:Admin');

// Inventories
Route::resource('inventories', 'InventoryController')->middleware('auth');

Route::get('inventory', 'InventoryController@index')->name('inventory')->middleware('auth');
Route::get('inventorycategory/{category}/', 'InventoryController@categoryInventory')->middleware('auth');

Route::get('add_item', 'InventoryController@create')->name('add_item')->middleware('auth');
Route::get('item/{id}', 'InventoryController@edit')->middleware('auth');
Route::get('print_item/{id}', 'InventoryController@show')->name('print_item')->middleware('auth');
Route::get('reports', 'InventoryController@reports')->name('reports')->middleware('auth');
Route::post('product_search','InventoryController@product_search')->name('product_search')->middleware('auth');
Route::post('fixItems','InventoryController@fixItems')->name('fixItems')->middleware('auth');


// Item Requests
Route::get('requests', 'InventoryController@requests')->name('requests')->middleware('auth');
Route::get('request/{id}', 'InventoryController@request')->name('request')->middleware('auth');

Route::post('new_request', 'InventoryController@new_request')->name('new_request')->middleware('auth');
Route::post('request_destroy','InventoryController@request_destroy')->name('request_destroy')->middleware('auth');
Route::post('update_request', 'InventoryController@update_request')->name('update_request')->middleware('auth');

// Facilities
Route::resource('facilities', 'FacilitiesController')->middleware('role:Admin');
Route::get('add_facility', 'FacilitiesController@create')->name('add_facility')->middleware('role:Admin');
Route::get('facility/{id}', 'FacilitiesController@edit')->middleware('role:Admin');
Route::get('facilityitems/{fid}', 'InventoryController@facilityItems')->middleware('role:Admin');

// Movements
Route::resource('movements', 'MovementController')->middleware('role:Admin');
Route::get('move_item/{id}', 'MovementController@edit')->name('move_item')->middleware('role:Admin');

// Audits
Route::resource('audits', 'AuditController')->middleware('role:Admin');

// Departments
Route::resource('departments', 'DepartmentController')->middleware('auth');
Route::get('add_department', 'DepartmentController@create')->name('add_department')->middleware('role:Admin');

// Categories
Route::resource('categories', 'CategoryController')->middleware('auth');


// Units
Route::resource('units', 'UnitController')->middleware('auth');
Route::get('add_unit', 'UnitController@create')->name('add_unit')->middleware('role:Admin');

// ACCESS AND AUTHENTICATIONS
Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('users', function(){
    return View('users');
})->middleware('role:Admin');

Route::get('edit_user/{id}', function(){
    return View('edit_user');
})->middleware('role:Admin');

Route::get('edit_user/{id}', 'CategoryController@editUser')->name('edit_user')->middleware('role:Admin');
Route::post('deleteUser', 'CategoryController@deleteUser')->name('deleteUser')->middleware('role:Admin');
Route::put('updateUser', 'CategoryController@updateUser')->name('updateUser')->middleware('role:Admin');

// HELP LINK
Route::get('help', function(){
    return View('help');
});
