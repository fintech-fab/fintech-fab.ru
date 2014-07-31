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
	// method names for comparing rules with data
	// TODO: not sure about constants, useful only for refactoring now, and rules consistency.
	const OP_BOOL = 'bool';
	const OP_GREATER = 'greater';
	const OP_GREATER_OR_EQUAL = 'greaterOrEqaul';
	const OP_LESS = 'less';
	const OP_LESS_OR_EQUAL = 'lessOrEqual';
	const OP_EQUAL = 'equal';
	const OP_NOT_EQUAL = 'notEqual';

	private $_aoRules;
	private $_aData;
	private $_aoFittedRules = [];

	/**
	 * @param $aRequestData
	 */
	public function calculate($aRequestData) // TODO: data and new object to static method, and set data.
	{
		$iTerminalId = $aRequestData['terminal_id'];
		$sEventSid = $aRequestData['event_sid'];
		$this->_aData = json_decode($aRequestData['data'], true);

		// receving rules from db
		$this->_aoRules = $this->getEventRules($iTerminalId, $sEventSid);

		// filtering and checking rules
		foreach ($this->_aoRules as $oRule) {
			if ($this->fitDataToRule($oRule)) {
				$this->_aoFittedRules[] = $oRule;
			}
		}
	}

	/**
	 * Get all fitted and worked rules
	 *
	 * @return Rule[]
	 */
	public function getFittedRules()
	{
		return $this->_aoFittedRules;
	}

	/**
	 * @param $oRule
	 *
	 * @return bool
	 */
	private function fitDataToRule($oRule)
	{
		$aoRuleTerms = json_decode($oRule->rule);
		$bIsDataTrue = true;

		foreach ($aoRuleTerms as $oRuleTerm) {
			if (array_key_exists($oRuleTerm->name, $this->_aData)) {
				$bCheckResult = $this->checkRule($oRuleTerm);
				$bIsDataTrue = (true && $bCheckResult);
			} else {
				$bIsDataTrue = false;
			}
		}

		return $bIsDataTrue;
	}

	/**
	 * @param $oRuleTerm
	 *
	 * @return bool
	 */
	private function checkRule($oRuleTerm)
	{
		// term present in data, go further
		$operatorName = constant(self::class . '::' . $oRuleTerm->operator);

		return $this->{$operatorName}($oRuleTerm->value, $this->_aData[$oRuleTerm->name]);
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function bool($ruleValue, $dataValue)
	{
		return $ruleValue && $dataValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function equal($ruleValue, $dataValue)
	{
		return $ruleValue == $dataValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function notEqual($ruleValue, $dataValue)
	{
		return $ruleValue != $dataValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function greater($ruleValue, $dataValue)
	{
		return $dataValue > $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $rulevalue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function greaterOrEqual($rulevalue, $dataValue)
	{
		return $dataValue >= $rulevalue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function less($ruleValue, $dataValue)
	{
		return $dataValue < $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param $ruleValue
	 * @param $dataValue
	 *
	 * @return bool
	 */
	private function lessOrEqual($ruleValue, $dataValue)
	{
		return $dataValue <= $ruleValue;
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

		// log found rules
		if (count($aoRules) > 0) {
			$sFoundRules = '';
			foreach ($aoRules as $oRule) {
				$sFoundRules .= "rule:=> " . $oRule->rule . "\n";
			}
			\Log::info("Found rules:\n" . $sFoundRules);
		}

		return $aoRules;
	}

}