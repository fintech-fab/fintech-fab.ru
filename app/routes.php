<?php
use App\Controllers\Site\MainController;

Route::get('/', array('as' => 'index', 'uses' => 'App\Controllers\Site\MainController@index'));
Route::get('/en', array('as' => 'en', 'uses' => 'App\Controllers\Site\MainController@en'));

Route::get('/vanguard', array('as' => 'vanguard', 'uses' => 'App\Controllers\Site\VanguardController@vanguard'));
Route::post('/vanguard', array('as' => 'vanguard.postOrder', 'uses' => 'App\Controllers\Site\VanguardController@postOrder'));

Route::get('/contact', array('as' => 'contact', 'uses' => MainController::class . '@contact'));
Route::get('/about', array('as' => 'about', 'uses' => 'App\Controllers\Site\MainController@about'));
Route::get('/projects', array('as' => 'projects', 'uses' => 'App\Controllers\Site\MainController@projects'));
Route::get('/mobile2care', array('as' => 'mobile2care', 'uses' => 'App\Controllers\Site\MainController@mobile2care'));
Route::get('/wlp', array('as' => 'wlp', 'uses' => 'App\Controllers\Site\MainController@wlp'));
Route::get('/anyany', array('as' => 'anyany', 'uses' => 'App\Controllers\Site\MainController@anyany'));

Route::get('notices', array(
		'before' => 'auth|testRole:messageSender',
		'as' => 'notices',
		'uses' => 'App\Controllers\Site\NoticesController@notices')
);
Route::post('notices/send', array(
		'before' => 'auth|testRole:messageSender',
		'as' => 'notices.send',
		'uses' => 'App\Controllers\Site\NoticesController@sendNotice')
);
Route::post('notices/theme/add', array(
		'before' => 'auth|testRole:messageSender',
		'as' => 'notices.theme.add',
		'uses' => 'App\Controllers\Site\NoticesController@addNewTheme')
);
Route::post('notices/theme/getMessage', array(
		'before' => 'auth|testRole:messageSender',
		'as' => 'notices.theme.getMessage',
		'uses' => 'App\Controllers\Site\NoticesController@getMessageOfTheme')
);

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
Route::get('gp', 'App\Controllers\Site\AuthController@socialNet');

Route::get('admin', array(
	'before' => 'auth|roleAdmin',
	'as'     => 'admin',
	'uses'   => 'App\Controllers\Site\AdminController@userRoles'
));

Route::get('admin/changeRole', array(
	'before' => 'auth|roleAdmin',
	'as'   => 'admin.changeRole',
	'uses' => 'App\Controllers\Site\AdminController@changeRole'
));

Route::group(array('before' => 'auth'), function () {
	Route::get('profile', array(
		'as'   => 'profile',
		'uses' => 'App\Controllers\User\UserProfileController@showUserProfile'
	));
	Route::post('upload/image', array(
		'as'   => 'upload/image',
		'uses' => 'App\Controllers\User\DownloadController@uploadImage',
	));
	Route::get('widgets/getPhoto', array(
		'as'   => 'widgets/getPhoto',
		'uses' => 'App\Controllers\User\UserProfileController@getPhoto',
	));
});

App::missing(function($exception)
{
	return Response::view('index', array(), 404);
});