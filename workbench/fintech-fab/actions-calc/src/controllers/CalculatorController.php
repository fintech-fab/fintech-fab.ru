<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Models\Event;
use View;

/**
 * Class CalculatorController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class CalculatorController extends BaseController
{
	protected $layout = 'main';

	public function manage()
	{
		$aoEvents = Event::whereTerminalId($this->iTerminalId)->get()->all();

		$content = View::make('ff-actions-calc::calculator.manage', [
			'events' => $aoEvents,
		]);
		$this->layout->content = $content;
	}
}