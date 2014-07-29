<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;

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

		$aoRules = $this->getEventRules($iTerminalId, $sEventSid);

		if (count($aoRules) > 0) {
			return ['status' => 'success', 'message' => '3 rules found'];
		}

//		foreach($aoRules as $rule) {
//
//		}

//
//		dd($aoRules);
	}

	/**
	 * @param int    $iTerminalId
	 * @param string $sEventSid
	 *
	 * @return Rule[]
	 */
	private function getEventRules($iTerminalId, $sEventSid)
	{
		$oEvent = Event::whereEventSid($sEventSid)->first();

		$aoRules = Rule::whereTerminalId($iTerminalId)
			->whereEventId($oEvent->id)
			->whereFlagActive(true)
			->get();

		return $aoRules;
	}
}