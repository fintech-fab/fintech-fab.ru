<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vk', 'App\Controllers\Site\MainController@vk');
Route::get('test', 'App\Controllers\Site\MainController@test');