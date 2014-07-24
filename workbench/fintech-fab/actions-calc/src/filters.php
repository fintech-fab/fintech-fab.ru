<?php
/**
 * File filters.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

use FintechFab\ActionsCalc\Models\Terminal;

Route::filter('ff.actions-calc.basic.auth', function () {
	$iClientId = Config::get('ff-actions-calc::app.terminal_id', 0);
	$iClientId = (int)$iClientId;
	$sClientKey = Config::get('ff-actions-calc::app.key');

	if ($iClientId > 0) {
		$terminal = Terminal::find($iClientId, ['id', 'key']);
		if(!$terminal->key == $sClientKey || $terminal->id != $iClientId) {
			return Redirect::to('login');
		}
	}

});