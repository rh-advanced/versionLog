<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Auth::routes();

Route::get('auth/logout', 'Auth\AuthController@logout');
Route::get('/', 'VersionLogController@loadextern');




Route::get('intern', [
    'middleware' => 'auth',
    'uses' => 'VersionLogController@loadinternpublish'
]);

Route::get('tv', [
    'middleware' => 'auth',
    'uses' => 'VersionLogController@loadinterntv'
]);


Route::get('intern/drafts', [
    'middleware' => 'auth',
    'uses' => 'VersionLogController@loadinterndraft'
]);




Route::get('/logout', 'Auth\AuthController@logout');

Route::resource('versionlog', 'VersionLogController');

Route::post('editid', 'VersionLogController@edit');


