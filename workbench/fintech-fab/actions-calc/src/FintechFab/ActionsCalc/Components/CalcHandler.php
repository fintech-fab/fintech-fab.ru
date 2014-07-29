<?php

namespace FintechFab\ActionsCalc\Components;

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;

/**
 * Class CalcHandler
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class CalcHandler
{
	private $_aoRules;
	private static $_data;

	const CALC_OP_AND = '[AND]';

	public function process($aRequestData)
	{
		$iTerminalId = $aRequestData['terminal_id'];
		$sEventSid = $aRequestData['event_sid'];
		self::$_data = $aRequestData['data'];

		// searching rules
		$this->_aoRules = $this->getEventRules($iTerminalId, $sEventSid);

		$this->filterRulesWith();
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

	/**
	 * @param $sData
	 *
	 * @return void
	 */
	private function filterRulesWith()
	{
		$oData = json_decode(self::$_data);

		$aoFitRules = [];

		foreach ($this->_aoRules as $oRule) {
			if ($this->isDataFitRule($oRule, $oData)) {
				$aoFitRules[] = $oRule;
			}
		}

		// log found rules
		if (count($this->_aoRules) > 0) {
			$sFoundRules = '';
			foreach ($this->_aoRules as $oRule) {
				$sFoundRules .= "rule:=> " . $oRule->rule . "\n";
			}
			\Log::info("Found rules:\n" . $sFoundRules);
		}
	}

	/**
	 * @param Rule $oRule
	 * @param      $oData
	 *
	 * @return bool
	 */
	private function isDataFitRule($oRule, $oData)
	{
		if (strpos($oRule->rule, self::CALC_OP_AND)) {
			$asRules = explode(self::CALC_OP_AND, $oRule->rule);
			dd($asRules);
		} else {

		}

		return true;
	}

}