<?php
Route::get('actions-calc/getRequest', array(
	'as'   => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest'
));