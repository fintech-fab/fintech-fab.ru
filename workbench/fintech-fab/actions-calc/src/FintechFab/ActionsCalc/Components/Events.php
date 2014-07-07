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
	 */
	public static function newEvent($term, $sid, $data)
	{
		$dataString = urldecode(http_build_query($data, null, ' | '));

		Event::create(array(
			'terminal_id' => $term,
			'sid'         => $sid,
			'data'        => $dataString,
		));
	}
}