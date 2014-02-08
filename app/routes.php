<?php

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));