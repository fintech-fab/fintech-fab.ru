<?php
/**
 * File filters.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

use FintechFab\ActionsCalc\Models\Terminal;

Route::filter('ff.actions-calc.basic.auth', function () {
	$iClientId = Config::get('ff-actions-calc::app.terminal_id');
	$iClientId = (int)$iClientId;
	$sClientKey = Config::get('ff-actions-calc::app.key');

	$terminal = Terminal::find($iClientId, ['id', 'key']);
	if (is_null($terminal) || $terminal->key != $sClientKey || $terminal->id != $iClientId) {
		return Redirect::route('login');
	}

});