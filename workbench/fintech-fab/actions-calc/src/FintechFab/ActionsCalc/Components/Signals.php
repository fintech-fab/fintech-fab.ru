<?php

namespace FintechFab\ActionsCalc\Components;


use FintechFab\ActionsCalc\Models\Signal;

class Signals
{

	public static function newSignal($eventId, $signalSid)
	{
		$sendSignal = new Signal;

		$sendSignal->event_id = $eventId;
		$sendSignal->signal_sid = $signalSid;
		$sendSignal->save();

		return $sendSignal;
	}

}