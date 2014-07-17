<?php
Route::post('actions-calc/getRequest', array(
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest'
));

Route::get('actions-calc/about', array(
	'as'   => 'calcAbout',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@about',
));

Route::get('actions-calc/edit', array(
	'as'   => 'calcEdit',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@edit',
));

Route::get('actions-calc/account', array(
	'as'   => 'calcAccount',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@account',
));

Route::post('actions-calc/account/newTerminal', array(
	'as'   => 'newTerminal',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@postNewTerminal',
));

Route::post('actions-calc/account/changeData', array(
	'as'   => 'changeDataCalc',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@postChangeData',
));

