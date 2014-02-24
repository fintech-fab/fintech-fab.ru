<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('probation', 'App\Controllers\Site\MainController@probation');
Route::get('test', 'App\Controllers\Site\TestController@index');
Route::post('probation', 'App\Controllers\Site\MainController@thank');


