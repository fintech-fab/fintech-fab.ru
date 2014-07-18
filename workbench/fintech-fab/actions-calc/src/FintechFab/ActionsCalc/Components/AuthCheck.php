<?php

namespace FintechFab\ActionsCalc\Components;

use Config;
use FintechFab\ActionsCalc\Models\Terminal;

class AuthCheck
{

	/**
	 * @return null|Terminal
	 */
	public static function getTerm()
	{
		$termId = Config::get('ff-actions-calc::termId');
		$terminal = Terminal::find($termId);

		return $terminal;
	}

}