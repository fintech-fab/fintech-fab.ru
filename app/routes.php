<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@index'));
Route::post('vanguard', 'App\Controllers\Site\VanguardController@order');