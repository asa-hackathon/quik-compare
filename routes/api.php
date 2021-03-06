<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('compare', 'ComparisionController@compare');
Route::get('do-compare', 'ComparisionController@doCompare');
Route::get('get-category', 'MappingController@getCategory');
Route::get('get-sub-category', 'MappingController@getSubCategory');
Route::get('get-mapping-data', 'MappingController@getMappingData');
Route::get('get-api-key-pairs', 'MappingController@getApiDropDownParams');

