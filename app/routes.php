<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('probation', 'App\Controllers\Site\MainController@probation');
Route::post('probation', 'App\Controllers\Site\MainController@order');
Route::get('thank', 'App\Controllers\Site\MainController@thank');


