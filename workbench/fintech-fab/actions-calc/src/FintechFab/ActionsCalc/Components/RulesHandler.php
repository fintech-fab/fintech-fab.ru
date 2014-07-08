<?php

namespace FintechFab\ActionsCalc\Components;


use Log;

class RulesHandler
{

	private $fitRules = array();

	/** Отдаёт подходящие правила
	 *
	 * @param array $rules
	 * @param array $data
	 *
	 * @return array
	 */
	public function getFitRules($rules, $data)
	{
		foreach ($rules as $rule) {

			$ruleArray = explode(' AND ', $rule['rule']);

			$result = true;
			foreach ($ruleArray as $strRule) {
				$result = $result && $this->checkRule($strRule, $data);
			}
			if ($result) {
				$this->fitRules[] = $rule;
			}

		}

		return $this->fitRules;
	}

	/** Проверка правила
	 *
	 * @param string $strRule
	 * @param array  $data
	 *
	 * @return bool
	 */
	private function checkRule($strRule, $data)
	{
		$strRule = trim($strRule);
		$ruleArray = explode(' ', $strRule);
		$method = $ruleArray[1];
		$key = $ruleArray[0];
		if (strpos($method, '!') !== false && !array_key_exists($key, $data)) {
			Log::info("Правило ($strRule) обязательное. Соответствующие данные не получены. Праило ложно.");

			return false;
		}
		if (!strpos($method, '!') !== false && !array_key_exists($key, $data)) {
			Log::info("Правило ($strRule) необязательное. Соответствующие данные не получены. Правило верно");

			return true;
		}
		$dataValue = $data[$key];
		switch ($ruleArray[2]) {
			case 'false':
				$ruleValue = false;
				break;
			case 'true':
				$ruleValue = true;
				break;
			default:
				$ruleValue = $ruleArray[2];
		}
		$nameOfMethod = $this->spotMethod($method);
		if ($nameOfMethod == 'unknown') {
			Log::info("В правиле указан неизвестный метод сравнения ($method)");

			return false;
		}
		$isTrueComparing = $this->$nameOfMethod($dataValue, $ruleValue);
		$answer = $isTrueComparing ? 'верно' : 'ложно';
		Log::info("Правило ($strRule)  $answer");

		return $isTrueComparing;

	}

	/** Определяет какой метод сравнения в правиле
	 *
	 * @param string $method
	 *
	 * @return string
	 */
	private function spotMethod($method)
	{
		switch ($method) {
			case '===':
			case '!==':
				$nameOfMethod = 'equal';
				break;
			case '<>':
			case '!<>':
				$nameOfMethod = 'notEqual';
				break;
			case '<':
			case '!<':
				$nameOfMethod = 'less';
				break;
			case '>':
			case '!>':
				$nameOfMethod = 'more';
				break;
			case '>=':
			case '=>':
			case '!>=':
			case '!=>':
				$nameOfMethod = 'moreOrEqual';
				break;
			case '<=':
			case '=<':
			case '!<=':
			case '!=<':
				$nameOfMethod = 'lessOrEqual';
				break;
			default:
				$nameOfMethod = 'unknown';
		}
		return $nameOfMethod;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function equal($dataValue, $ruleValue)
	{
		return $dataValue == $ruleValue;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function notEqual($dataValue, $ruleValue)
	{
		return $dataValue != $ruleValue;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function less($dataValue, $ruleValue)
	{
		return $dataValue < $ruleValue;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function more($dataValue, $ruleValue)
	{
		return $dataValue > $ruleValue;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function moreOrEqual($dataValue, $ruleValue)
	{
		return $dataValue >= $ruleValue;
	}

	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	public function lessOrEqual($dataValue, $ruleValue)
	{
		return $dataValue <= $ruleValue;
	}


}