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

Route::get('/', ['as' => 'home', 'uses' => 'Auth0Controller@index']);
Route::get('/login', ['as' => 'login', 'uses' => 'Auth0Controller@login']);
Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth0Controller@logout'])->middleware('auth');
Route::get('/dump', ['as' => 'dump', 'uses' => 'Auth0Controller@dump', 'middleware' => 'auth'])->middleware('auth');

Route::get('/callback', ['as' => 'logincallback', 'uses' => 'Auth0Controller@callback']);

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
	Route::post('/shareProject', 'Api\GymingTools@shareProject');
	Route::post('/saveNewModelName', 'Api\GymingTools@saveNewModelName');
	Route::get('/gantt-app', 'GanttController@index')->name('get-gantt');

	Route::get('/my-project-resources', 'ResourceController@create')->name('get-project-resources');

	Route::resource('/resource', 'ResourceController');
});
Route::get('/share/{id}', 'GymingTools@shareProject');
