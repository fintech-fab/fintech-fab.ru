<?php
Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@vanguard'));
Route::post('vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@postOrder'));

Route::post('auth', array('as' => 'auth', 'uses' => 'App\Controllers\Site\AuthController@postAuth'));
Route::post('registration', array(
	'as'   => 'registration',
	'uses' => 'App\Controllers\Site\AuthController@postRegistration'
));
Route::get('registration', array(
	'before' => 'guest',
	'as'     => 'registration',
	'uses'   => 'App\Controllers\Site\AuthController@registration'
));

Route::get('logout', array('as' => 'logout', 'uses' => 'App\Controllers\Site\AuthController@logout'));

Route::get('vk', 'App\Controllers\Site\AuthController@socialNet');
Route::get('fb', 'App\Controllers\Site\AuthController@socialNet');

Route::get('admin', array(
	'before' => 'auth|roleAdmin',
	'as'     => 'admin',
	'uses'   => 'App\Controllers\Site\UserProfileController@showAdmin'
));

Route::get('TableForAdmin', array(
	'as'   => 'WorkAdmin',
	'uses' => 'App\Controllers\Site\AdminController@TableForAdmin'
));

Route::get('changeRole', array(
	'as'   => 'changeRole',
	'uses' => 'App\Controllers\Site\AdminController@changeRole'
));

Route::group(array('before' => 'auth'), function () {
	Route::get('profile', array(
		'as'   => 'profile',
		'uses' => 'App\Controllers\Site\UserProfileController@showUserProfile'
	));

});
