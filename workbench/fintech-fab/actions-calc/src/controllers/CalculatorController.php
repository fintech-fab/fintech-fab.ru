<?php

namespace FintechFab\ActionsCalc\Controllers;

use Input;
use FintechFab\ActionsCalc\Models\Terminal;
use FintechFab\ActionsCalc\Models\Event;
use League\FactoryMuffin\Exceptions\ModelException;
use View;
use FintechFab\ActionsCalc\Models\Rule;

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
		$oTerminal = Terminal::with(['events', 'signals'])->get()->find($this->iTerminalId);

		$content = View::make('ff-actions-calc::calculator.manage')
			->nest('_events', 'ff-actions-calc::calculator._events', ['events' => $oTerminal->events])
			->nest('_rules', 'ff-actions-calc::calculator._rules', ['rules' => $oTerminal->rules])
			->nest('_signals', 'ff-actions-calc::calculator._signals', ['signals' => $oTerminal->signals]);

		$this->layout->content = $content;
	}

	/**
	 * Getting all rules
	 */
	public function getEventRules()
	{
		$iEventId = (int)Input::get('event_id');
		$aoRules = Rule::whereEventId($iEventId)->get();

		$sResult = '';

		if ($aoRules->count() > 0) {
			$sResult = View::make('ff-actions-calc::calculator._event_rules', ['rules' => $aoRules]);
		}

		return $sResult;
	}

	/**
	 * Toggle rule active
	 */
	public function toggleRuleFlag()
	{
		$this->layout = null;
		$aRuleData = Input::only('id', 'flag_active');

		/** @var Rule $oRule */
		$oRule = Rule::find((int)$aRuleData['id']);
		$iRuleFlag = $aRuleData['flag_active'] == 'true' ? 1 : 0;
		$oRule->flag_active = $iRuleFlag;

		try {
			if ($oRule->save()) {
				echo json_encode(['status' => 'success', 'message' => '#' . $aRuleData['id'] . ' Правило сохранено.']);
			}
		} catch (ModelException $e) {
			echo json_encode([
				'status'        => 'error',
				'message'       => 'Ошибка сохранение правила.',
				'error_message' => $e->getMessage()
			]);
		}
	}

	public function eventsTableUpdate()
	{

		$aoEvents = Event::where('terminal_id', '=', $this->iTerminalId)->orderBy('created_at', 'desc')->paginate(10);

		return View::make('ff-actions-calc::calculator._events_table', [
			'events' => $aoEvents
		]);
	}
}