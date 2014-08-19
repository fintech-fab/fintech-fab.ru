<?php

namespace FintechFab\ActionsCalc\Controllers;

use Config;
use FintechFab\ActionsCalc\Models\Terminal;
use View;

/**
 * Class CalculatorController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class CalculatorController extends BaseController
{
	protected $iTerminalId;
	protected $layout = 'main';

	public function __construct()
	{
		$this->iTerminalId = Config::get('ff-actions-calc::terminal_id');
	}

	public function manage()
	{
		$oTerminal = Terminal::with(['events', 'signals'])->get()->find($this->iTerminalId);

		$content = View::make('ff-actions-calc::calculator.manage')
			->nest('_events', 'ff-actions-calc::calculator._events', ['events' => $oTerminal->events])
			->nest('_rules', 'ff-actions-calc::calculator._rules', ['rules' => $oTerminal->rules])
			->nest('_signals', 'ff-actions-calc::calculator._signals', ['signals' => $oTerminal->signals]);

		$this->layout->content = $content;
	}
}