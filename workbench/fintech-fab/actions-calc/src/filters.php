<?php
use FintechFab\ActionsCalc\Components\AuthCheck;

Route::filter('calcRegistered', function () {
	if (AuthCheck::getTerm() == null) {
		return Redirect::route('calcRegistration');
	}
});

Route::filter('calcNotRegistered', function () {
	if (AuthCheck::getTerm() != null) {
		return Redirect::route('calcAccount');
	}
});