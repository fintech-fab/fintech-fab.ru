<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Terminal;
use Config;

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
		/** *@var Terminal $terminal */
		$terminal = Terminal::find($aRequestData['terminal_id'], ['id', 'key']);

		if (is_null($terminal)) {
			return false;
		}

		$signature = sha1($terminal->id . '|' . $aRequestData['event_sid'] . '|' . $terminal->key);

		return $signature == $aRequestData['auth_sign'];
	}

	/**
	 * Authenticate client by hist config terminal_id
	 *
	 * @return bool
	 */
	public static function isClientRegistered()
	{
		$iClientId = Config::get('ff-actions-calc::terminal_id');
		$iClientId = (int)$iClientId;

		$terminal = Terminal::find($iClientId, ['id']);

		return (!is_null($terminal));
	}
}