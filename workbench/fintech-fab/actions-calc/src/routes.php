<?php
/**
 * File routes.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Route::get('/actions-calc/manage', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@manage'
]);

Route::post('/actions-calc/manage/get-event-rules', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@getEventRules'
]);

// main entry point
Route::post('actions-calc', [
	'as'     => 'getRequest',
	'uses'   => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/login', [
	'as'   => 'login',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@login'
]);