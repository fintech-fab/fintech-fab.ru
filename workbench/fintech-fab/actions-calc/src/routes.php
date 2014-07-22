<?php
/**
 * File routes.php
 * 
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Route::post('actions-calc/getRequest', [
	'as' => 'getRequest',
	'uses' => 'FintechFab\ActionsCalc\Controllers\RequestController@getRequest'
]);