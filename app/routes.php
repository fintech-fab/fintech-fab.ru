<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@vanguard'));
Route::post('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@postOrder'));
Route::post('auth', array('as' => 'auth', 'uses' => 'App\Controllers\Site\AuthController@postAuth'));
Route::post('registration', array('as' => 'registration', 'uses' => 'App\Controllers\Site\AuthController@postRegistration'));
Route::get('registration', array('as' => 'registration', 'uses' => 'App\Controllers\Site\VanguardController@registration'));
Route::get('logout', array('as' => 'logout', 'uses' => 'App\Controllers\Site\AuthController@logout'));

Route::get('probation', 'App\Controllers\Site\MainController@probation');
Route::post('probation', 'App\Controllers\Site\MainController@thank');

Route::get('vk', 'App\Controllers\Site\MainController@vk');
Route::get('test', 'App\Controllers\Site\MainController@test');