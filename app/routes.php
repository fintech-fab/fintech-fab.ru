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
	'uses'   => 'App\Controllers\Site\VanguardController@registration'
));

Route::get('logout', array('as' => 'logout', 'uses' => 'App\Controllers\Site\AuthController@logout'));

Route::get('vk', 'App\Controllers\Site\AuthController@vk');
Route::get('fb', 'App\Controllers\Site\AuthController@fb');

Route::group(array('before' => 'auth'), function () {
	Route::get('profile', array(
		'as'   => 'profile',
		'uses' => 'App\Controllers\Site\UserProfileController@showUserProfile'
	));

});
Route::post('login', array('as' => 'login', 'uses' => 'App\Controllers\Site\Login@postAuth'));
