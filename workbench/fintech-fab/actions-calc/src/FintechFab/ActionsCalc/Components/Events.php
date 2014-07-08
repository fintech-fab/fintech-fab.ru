<?php

namespace FintechFab\ActionsCalc\Components;


use FintechFab\ActionsCalc\Models\Event;

class Events
{

	/** Запись в базу нового события
	 *
	 * @param $term
	 * @param $sid
	 * @param $data
	 *
	 * @return Event
	 */
	public static function newEvent($term, $sid, $data)
	{
		$dataString = urldecode(http_build_query($data, null, ' | '));

		$event = new Event;
		$event->terminal_id = $term;
		$event->sid = $sid;
		$event->data = $dataString;
		$event->save();

		return $event;
	}
}