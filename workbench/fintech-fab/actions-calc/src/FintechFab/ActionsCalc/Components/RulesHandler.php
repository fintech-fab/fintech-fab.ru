<?php

namespace FintechFab\ActionsCalc\Components;


use FintechFab\ActionsCalc\Models\Rule;
use Log;

class RulesHandler
{

	private $fitRules = array();

	/** Отдаёт подходящие правила
	 *
	 * @param Rule[] $rules
	 * @param array  $data
	 *
	 * @return Rule[]|null
	 */
	public function getFitRules($rules, $data)
	{
		foreach ($rules as $rule) {

			$ruleArray = explode(' AND ', $rule->rule);

			$result = true;
			foreach ($ruleArray as $strRule) {
				$result = $result && $this->checkRule($strRule, $data);
			}
			if ($result) {
				$this->fitRules[] = $rule;
			}

		}

		return count($this->fitRules) == 0 ? null : $this->fitRules;
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

		$matches = [];
		$isMatch = preg_match('/^(.*) ([\<\>\=]+) (.*)$/', $strRule, $matches);
		if (!$isMatch) {
			return false;
		}
		list(, $key, $method, $value) = $matches;

		$keyExists = array_key_exists($key, $data);
		/*$keyRequired = strpos($key, '!') === 0;*/

		if (!$keyExists) {
			// закоментировано определение обязательности правила по !
			/*if ($keyRequired) {
				Log::info("Правило ($strRule) обязательное. Соответствующие данные не получены. Праило ложно.");

				return false;
			} else {
				Log::info("Правило ($strRule) необязательное. Соответствующие данные не получены. Правило верно");

				return true;
			}*/
			Log::info("Правило ($strRule). Соответствующие данные не получены. Праило ложно.");

			return false;

		}

		$dataValue = $data[$key];
		switch ($value) {
			case 'false':
				$ruleValue = false;
				break;
			case 'true':
				$ruleValue = true;
				break;
			default:
				$ruleValue = $value;
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
			case '=':
			case '==':
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

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function equal($dataValue, $ruleValue)
	{
		return $dataValue == $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function notEqual($dataValue, $ruleValue)
	{
		return $dataValue != $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function less($dataValue, $ruleValue)
	{
		return $dataValue < $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function more($dataValue, $ruleValue)
	{
		return $dataValue > $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function moreOrEqual($dataValue, $ruleValue)
	{
		return $dataValue >= $ruleValue;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	/**
	 * @param string $dataValue
	 * @param string $ruleValue
	 *
	 * @return bool
	 */
	private function lessOrEqual($dataValue, $ruleValue)
	{
		return $dataValue <= $ruleValue;
	}


}