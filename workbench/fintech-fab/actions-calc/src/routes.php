<?php
/**
 * File routes.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

// main entry point
Route::post('actions-calc', [
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest',
]);

// auth registration
Route::any('/actions-calc/registration', [
	'as'   => 'auth.registration',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@registration'
]);

Route::group(['before' => 'ff-actions-calc.auth'], function () {

	// signals
	Route::resource('signal', 'FintechFab\ActionsCalc\Controllers\SignalController');

	// events
	// delete event
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
	Route::get('/actions-calc/events/table{page?}', [
		'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@updateEventsTable',
	]);
	// events search
	Route::get('actions-calc/event/search', [
		'uses' => 'FintechFab\ActionsCalc\Controllers\EventController@search',
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
	// rule create
	Route::match(['GET', 'POST'], '/actions-calc/rule/create', [
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

	// managing all records
	Route::get('/actions-calc/manage', [
		'as'   => 'calc.manage',
		'uses' => 'FintechFab\ActionsCalc\Controllers\ManageController@manage'
	]);

	// auth profile
	Route::any('/actions-calc/profile', [
		'as'   => 'auth.profile',
		'uses' => 'FintechFab\ActionsCalc\Controllers\AuthController@profile'
	]);

});
