<?php

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
Route::get('/Tenant/Delete', function () {
    return view('delete');
});

Route::group(['namespace' => 'Api\V1\System'], function () {
    Route::post('/', 'RegisterCustomerController@createTenant')->name('register-customer');
    Route::post('/Tenant/Delete', 'RegisterCustomerController@deleteTenant')->name('delete-customer');
});


/**************** Tenant Specific Route ******************/
Route::group(['middleware' => 'enforce.tenancy'], function () {

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');
});