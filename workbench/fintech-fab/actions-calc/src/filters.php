<?php
use FintechFab\ActionsCalc\Components\AuthCheck;

Route::filter('checkTerm', function () {
	if (!AuthCheck::findTerm()) {
		return Redirect::route('registration');
	}
});