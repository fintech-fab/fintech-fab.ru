<?php
/**
 * File routes.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Route::get('/actions-calc/manage', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\ManageController@manage'
]);
// update events table
Route::get('/actions-calc/manage/update-events-table', [
	'as'   => 'update.events.table',
	'uses' => 'FintechFab\ActionsCalc\Controllers\ManageController@updateEventsTable'
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

// events -> rules:
// get events rules
Route::post('/actions-calc/manage/get-event-rules', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\ManageController@getEventRules'
]);
// toggle events rules flag
Route::post('/actions-calc/manage/toggle-rule-flag', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\ManageController@toggleRuleFlag'
]);

// event -> rules:
// rule update
Route::get('/actions-calc/rule/create', [
	'as'   => 'rule.create',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RuleController@create',
]);
// event -> rules:
// rule update
Route::match(['POST', 'GET'], '/actions-calc/rule/update/{id?}', [
	'as'   => 'rule.update',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RuleController@update',
])->where('id', '[0-9]+');
// event -> rules:
// rule delete
Route::post('/actions-calc/rule/delete/{id}', [
	'as'   => 'rule.delete',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RuleController@delete'
])->where('id', '[0-9]+');

// main entry point
Route::post('actions-calc', [
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

Route::get('actions-calc/register', [
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@register'
]);