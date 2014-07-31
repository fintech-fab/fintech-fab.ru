<?php
Route::post('actions-calc/getRequest', array(
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest'
));

Route::get('actions-calc/about', array(
	'as'   => 'calcAbout',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@about',
));

Route::get('actions-calc/registration', array(
	'before' => 'calcNotRegistered',
	'as'     => 'calcRegistration',
	'uses'   => 'FintechFab\ActionsCalc\Controllers\AccountController@registration',
));

Route::group(array(
	'before'    => 'calcRegistered',
	'prefix'    => 'actions-calc',
	'namespace' => 'FintechFab\ActionsCalc\Controllers',
), function () {
	Route::get('account', array(
		'as'   => 'calcAccount',
		'uses' => 'AccountController@account',
	));
	Route::get('tableRules', array(
		'as'   => 'calcTableRules',
		'uses' => 'TablesController@tableRules',
	));
	Route::get('tableEvents', array(
		'as'   => 'calcTableEvents',
		'uses' => 'TablesController@tableEvents',
	));
	Route::get('tableSignals', array(
		'as'   => 'calcTableSignals',
		'uses' => 'TablesController@tableSignals',
	));
});

Route::post('actions-calc/account/newTerminal', array(
	'as'   => 'calcNewTerminal',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@postNewTerminal',
));

Route::post('actions-calc/account/changeData', array(
	'as'   => 'changeDataCalc',
	'uses' => 'FintechFab\ActionsCalc\Controllers\AccountController@postChangeData',
));


Route::post('actions-calc/tableEvents/changeData/', array(
	'as'   => 'calcChangeDataEvents',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postChangeDataEvents',
));

Route::post('actions-calc/tableEvents/addData/', array(
	'as'   => 'calcAddDataEvents',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postAddDataEvents',
));

Route::post('actions-calc/tableEvents/getRules/', array(
	'as'   => 'calcViewRule',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postViewRule',
));


Route::post('actions-calc/tableSignals/changeData/', array(
	'as'   => 'calcChangeDataSignals',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postChangeDataSignals',
));

Route::post('actions-calc/tableSignals/addData/', array(
	'as'   => 'calcAddDataSignals',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postAddDataSignals',
));

Route::post('actions-calc/tableRule/changeFlagRule/', array(
	'as'   => 'calcChangeFlagRule',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postChangeFlagRule',
));

Route::post('actions-calc/tableRules/changeData/', array(
	'as'   => 'calcChangeDataRule',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postChangeDataRule',
));

Route::post('actions-calc/tableRules/addData/', array(
	'as'   => 'calcAddDataRule',
	'uses' => 'FintechFab\ActionsCalc\Controllers\TablesController@postAddDataRule',
));
