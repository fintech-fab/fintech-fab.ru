<?php

namespace FintechFab\ActionsCalc\Components;

use Config;
use FintechFab\ActionsCalc\Models\Terminal;

class AuthCheck
{


	public static function findTerm()
	{
		$termId = Config::get('ff-actions-calc::termId');
		$terminal = Terminal::find($termId);
		if ($terminal == null) {
			return false;
		}

		return true;
	}

}