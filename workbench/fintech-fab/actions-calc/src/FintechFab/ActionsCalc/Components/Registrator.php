<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\RegisterEvent;
use Log;

/**
 * Class ResultRegistrator
 * Register incoming events and outcome signals
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class Registrator
{

	/**
	 * Registering incoming event
	 *
	 * @param array $aRequestData
	 *
	 * @return void
	 */
	public static function registerEvent($aRequestData)
	{
		$oEvent = Event::whereEventSid($aRequestData['event_sid'])->first();
		$aEventAttributes = $oEvent->getAttributes();

		$aEventAttributes['event_id'] = $aEventAttributes['id'];
		$aEventAttributes['data'] = $aRequestData['data'];

		unset($aEventAttributes['id']);
		unset($aEventAttributes['created_at']);
		unset($aEventAttributes['updated_at']);

		$oRegisterEvent = new RegisterEvent();
		$oRegisterEvent->setRawAttributes($aEventAttributes);
		if ($oRegisterEvent->save()) {
			Log::info('Register event', $aEventAttributes);
		}
	}

	/**
	 * Register outgoing signal
	 *
	 * @param $aSignalAttributes
	 *
	 * @return void
	 */
	public static function registerSignal($aSignalAttributes)
	{

	}
} 