<?php
/**
 * File routes.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

// main entry point
Route::post('actions-calc', [
	'as'     => 'getRequest',
	'uses'   => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/login', [
	'as'   => 'login',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@login'
]);