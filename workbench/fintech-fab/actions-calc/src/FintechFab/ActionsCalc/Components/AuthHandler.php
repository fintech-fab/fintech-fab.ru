<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Terminal;

/**
 * Class AuthHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class AuthHandler
{

	/**
	 * Compare signatures
	 *
	 * @param $aRequestData
	 *
	 * @return bool
	 */
	public static function checkSign($aRequestData)
	{
		$terminal = Terminal::find($aRequestData['terminal_id'], ['id', 'key']);

		if (is_null($terminal)) {
			return false;
		}

		$signature = sha1($terminal->id . '|' . $aRequestData['event_sid'] . '|' . $terminal->key);

		return $signature == $aRequestData['auth_sign'];
	}
}