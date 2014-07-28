<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Components\AuthHandler;
use FintechFab\ActionsCalc\Models\Terminal;

/**
 * Class CoreHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class CoreHandler
{

	public function process($aRequestData)
	{
		$iTerminalId = $aRequestData['terminal_id'];
		$aoRules = Terminal::find($iTerminalId)->rules();



		dd($aoRules);
	}
}