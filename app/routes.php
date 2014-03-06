<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@index'));
Route::post('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@postOrder'));
Route::post('auth', array('as' => 'auth', 'uses' => 'App\Controllers\Site\AuthController@postAuth'));
Route::get('registration', array('as' => 'registration', 'uses' => 'App\Controllers\Site\VanguardController@registration'));

Route::get('probation', 'App\Controllers\Site\MainController@probation');
Route::post('probation', 'App\Controllers\Site\MainController@thank');

Route::get('vk', 'App\Controllers\Site\AuthController@vk');