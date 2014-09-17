<?php

namespace FintechFab\ActionsCalc\Components;

use Config;
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
		/** *@var Terminal $terminal */
		// выбирать только нужные данные - хорошая практика
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
		// проще можно. и еще terminal_id не похоже на $iClientId
		$iClientId = Config::get('ff-actions-calc::terminal_id');
		return Terminal::find($iClientId, ['id']);
	}
}