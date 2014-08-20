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
Route::post('/actions-calc/manage/toggle-rule-flag', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@toggleRuleFlag'
]);

// Event
Route::get('/actions-calc/event/create', [
	'as' => 'event.create',
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@create'
]);

// main entry point
Route::post('actions-calc', [
	'as'     => 'getRequest',
	'uses'   => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/register', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@register'
]);