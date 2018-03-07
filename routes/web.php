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
	Route::get('/gyming-tools', 'GymingTools@index');
	Route::post('/addNewProject', 'Api\GymingTools@addNewProject');
	Route::post('/addNewProjectModel', 'Api\GymingTools@addNewProjectModel');
	Route::post('/addNewModelItem', 'Api\GymingTools@addNewModelItem');
	Route::post('/updateModelItem', 'Api\GymingTools@updateModelItem');
	Route::get('/getProjects', 'Api\GymingTools@getProjects');
	Route::post('/getCanvas', 'Api\GymingTools@getCanvas');
	Route::get('/getSWOTProjects', 'Api\GymingTools@getSWOTProjects');
	Route::put('/saveModelItem', 'Api\GymingTools@saveModelItem');
	Route::post('/deleteItem', 'Api\GymingTools@deleteItem');
});

