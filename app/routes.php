<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@index'));
Route::post('vanguard', 'App\Controllers\Site\VanguardController@postOrder');

Route::get('probation', 'App\Controllers\Site\MainController@probation');
Route::post('probation', 'App\Controllers\Site\MainController@thank');

Route::get('vk', 'App\Controllers\Site\MainController@vk');
Route::get('test', 'App\Controllers\Site\MainController@test');