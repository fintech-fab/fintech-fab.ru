<?php
/**
 * File filters.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

use FintechFab\ActionsCalc\Components\AuthHandler;

Route::filter('ff.actions-calc.basic.auth', function () {
	if (AuthHandler::isClientRegistered() === false) {
		return Redirect::route('login');
	}
});
