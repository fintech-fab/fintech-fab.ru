<?php
/**
 * File routes.php
 * 
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Route::post('actions-calc/getRequest', [
	'before' => 'ff.actions-calc.basic.auth',
	'as' => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest'
]);

Route::get('actions-calc/login', [
	'as' => 'login',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@login'
]);