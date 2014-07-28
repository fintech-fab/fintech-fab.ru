<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Components\AuthHandler;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Terminal;
use Response;

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
		$sEventSid = $aRequestData['event_sid'];

		$oEvent = Event::whereEventSid($sEventSid)->first();
//		if (Event::sidExists($sEventSid)) {
//		}

//		$oEvents = Terminal::find($iTerminalId)->events()

//		$aoRules = Rule::where('event_id', '=', $iTerminalId);
		$aoRules = Rule::whereTerminalId($iTerminalId)
			->whereEventId($oEvent->id)
			->get()->all();

//		if (is_null($aoRules)) {
//			Response::json(['status' => 'success', 'No rules found.']);
//		}
//
		dd($aoRules);
	}
}