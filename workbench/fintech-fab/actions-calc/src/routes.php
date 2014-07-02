<?php
Route::get('actions-calc/getRequest', array(
	'as'   => 'getRequest',
	'uses' => 'RequestController@getRequest'
));