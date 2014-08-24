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

// update events table
//Route::post('/actions-calc/manage', [
//	'as' => 'event.table.update',
//	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@eventsTableUpdate'
//]);

Route::post('/actions-calc/manage/get-event-rules', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@getEventRules'
]);
Route::post('/actions-calc/manage/toggle-rule-flag', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\CalculatorController@toggleRuleFlag'
]);

// Events
Route::post('/actions-calc/event/create', [
	'as'   => 'event.create',
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@create'
]);
Route::get('/actions-calc/event/create', [
	'as' => 'event.create', function () {
		return View::make('ff-actions-calc::event.create');
	}
]);
// events table pagination
Route::get('actions-calc/events/table{page?}', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@updateEventTable',
]);


// main entry point
Route::post('actions-calc', [
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/register', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@register'
]);