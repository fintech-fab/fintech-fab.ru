<?php
use FintechFab\ActionsCalc\Components\AuthCheck;

Route::filter('checkTerm', function () {
	if (AuthCheck::getTerm() == null) {
		return Redirect::route('calcRegistration');
	}
});