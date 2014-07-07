<?php

namespace FintechFab\ActionsCalc\Components;


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

			$rulesArray = explode(' AND ', $rule['rule']);

			$result = true;
			foreach ($rulesArray as $strRule) {
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
			return false;
		}
		if (!strpos($method, '!') !== false && !array_key_exists($key, $data)) {
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
		$isTrueComparing = $this->$nameOfMethod($dataValue, $ruleValue);

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