<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/


use FintechFab\Models\RoleUser;

App::before(function ($request) {
	//
});


App::after(function ($request, $response) {
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
	if (Auth::guest()) {
		return Redirect::guest('registration');
	}

});

// запоминание обратного url при переходе на авторизацию или регистрацию
Route::filter('referrer', function () {
	$backUrl = urldecode(Input::get('back'));
	if ($backUrl) {
		$validator = Validator::make(
			array('url' => $backUrl),
			array('url' => 'required|url')
		);
		if ($validator->passes()) {
			Session::set('authReferrerUrl', $backUrl);
		}
	}
});


Route::filter('auth.basic', function () {
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
	if (Auth::check()) {
		return Redirect::to('profile');
	}
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
	if (Session::token() != Input::get('_token')) {
		throw new Illuminate\Session\TokenMismatchException;
	}
});


Route::filter('roleAdmin', function ()
{
	$res = RoleUser::whereRoleId(1)->whereUserId(Auth::user()->id)->count();
	if ($res == 0) {
		return Redirect::to('profile');
	}
});

Route::filter('testRole', function ($route, $request, $value = '') {
	if(! Auth::user()->isCompetent($value)) {
		return Redirect::to('profile');
	}
});

Route::filter('lang', function(){
	App::setLocale('ru');
});
