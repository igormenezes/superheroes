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

Route::get('/', 'SuperHeroesController@show');
Route::get('create', 'SuperHeroesController@create');
Route::get('remove/{id}', 'SuperHeroesController@remove');
Route::get('details/{id}', 'SuperHeroesController@details');
Route::get('edit/{id}', 'SuperHeroesController@edit');

Route::post('add-image', 'SuperHeroesController@addImage');
Route::post('remove-image', 'SuperHeroesController@removeImage');
Route::post('save', 'SuperHeroesController@save');
Route::post('update', 'SuperHeroesController@update');