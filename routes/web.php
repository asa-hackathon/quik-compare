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
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/create-mapping', array('as' => 'create-mapping', 'uses' => 'MappingController@showCreateMapping'));
Route::post('/add-mapping', array('as' => 'add-mapping', 'uses' => 'MappingController@addMapping'));
Route::get('/mapping-dashboard', array('as' => 'mapping-dashboard', 'uses' => 'MappingController@mappingDashboard'));
