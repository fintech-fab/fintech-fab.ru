<?php
use FintechFab\ActionsCalc\Components\AuthCheck;

Route::filter('auth', function () {
	if (AuthCheck::getTerm() == null) {
		return Redirect::route('calcRegistration');
	}
});

Route::filter('notAuth', function () {
	if (AuthCheck::getTerm() != null) {
		return Redirect::route('calcAccount');
	}
});