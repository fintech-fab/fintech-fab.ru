<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\RegisterEvent;
use FintechFab\ActionsCalc\Models\RegisterSignal;
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
	 * @param array       $aSignalAttributes
	 * @param bool        $setFlagUrl
	 * @param bool        $setFlagQueue
	 * @param null|string $sResultHash
	 *
	 * @return void
	 */
	public static function registerSignal($aSignalAttributes, $setFlagUrl = false, $setFlagQueue = false,
	                                      $sResultHash = null)
	{
		$aSignalAttributes['signal_id'] = $aSignalAttributes['id'];

		unset($aSignalAttributes['id']);
		unset($aSignalAttributes['created_at']);
		unset($aSignalAttributes['updated_at']);

		if ($setFlagUrl) {
			$aSignalAttributes['flag_url'] = true;
		}
		if ($setFlagQueue) {
			$aSignalAttributes['flag_queue'] = true;
		}
		if (!is_null($sResultHash)) {
			$aSignalAttributes['result_hash'] = $sResultHash;
		}

		$oRegisterSignal = new RegisterSignal();
		$oRegisterSignal->setRawAttributes($aSignalAttributes);

		if ($oRegisterSignal->save()) {
			$sWhichFlag = $setFlagUrl ? 'CURL' : 'Result to Queue to Queue XD';
			Log::info("Register signal($sWhichFlag)", $aSignalAttributes);
		}
	}
} 