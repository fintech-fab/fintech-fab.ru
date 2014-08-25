<?php
/**
 * File routes.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Route::get('/actions-calc/manage', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@manage'
]);
// update events table
Route::get('/actions-calc/manage/update-events-table', [
	'as'   => 'update.events.table',
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@updateEventsTable'
]);

// events
// delete event //TODO: check if user allowed to do sturff. Through filter.
Route::post('/actions-calc/event/delete', [
	'as'   => 'event.delete',
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@delete'
]);
// update event
Route::match(['POST', 'GET'], '/actions-calc/event/update/{id?}', [
	'as'   => 'event.update',
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@update',
])->where('id', '[0-9]+');
// create event
Route::post('/actions-calc/event/create', [
	'as'   => 'event.create',
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@create'
]);
// events table pagination
Route::get('actions-calc/events/table{page?}', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@updateEventsTable',
]);

// events -> rules
Route::post('/actions-calc/manage/get-event-rules', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@getEventRules'
]);
Route::post('/actions-calc/manage/toggle-rule-flag', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@toggleRuleFlag'
]);

// main entry point
Route::post('actions-calc', [
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/register', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@register'
]);