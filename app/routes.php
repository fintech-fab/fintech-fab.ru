<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('about', 'App\Controllers\Site\MainController@about');
Route::get('test', 'App\Controllers\Site\TestController@index');
