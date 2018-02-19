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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/swot', 'SWOTController@index')->name('swot');
	Route::post('/saveSWOT', 'SWOTController@save');
	Route::get('/getSWOT', 'SWOTController@retrieve');
	Route::get('/business-canvas', 'BusinessCanvas@index');
	Route::post('/addNewProject', 'Api\BusinessCanvas@addNewProject');
	Route::post('/addNewProjectModel', 'Api\BusinessCanvas@addNewProjectModel');
	Route::post('/addNewModelItem', 'Api\BusinessCanvas@addNewModelItem');
	Route::post('/updateModelItem', 'Api\BusinessCanvas@updateModelItem');
	Route::get('/getProjects', 'Api\BusinessCanvas@getProjects');
	Route::post('/getCanvas', 'Api\BusinessCanvas@getCanvas');
	Route::get('/getSWOTProjects', 'Api\BusinessCanvas@getSWOTProjects');
	Route::put('/saveModelItem', 'Api\BusinessCanvas@saveModelItem');
	Route::post('/deleteItem', 'Api\BusinessCanvas@deleteItem');
});

