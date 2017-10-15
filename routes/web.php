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
    return redirect('search');
});

Route::get('search', 'SearchController@index');
Route::get('search', 'SearchController@search');
Route::get('total/{type}', 'SearchController@getTotalRecord');
Route::get('search/{type}', 'SearchController@search');
Route::get('searchUpdate/{type}', 'SearchController@searchUpdate');
Route::get('filter/{name}', 'SearchController@prepareFilter');