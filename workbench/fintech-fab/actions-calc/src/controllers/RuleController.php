<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use Validator;
use Input;
use View;
use Request;
use App;

class RuleController extends BaseController
{

	public function create()
	{
		if (Request::isMethod('GET')) {
			$signals = Signal::all(['id', 'signal_sid', 'name']);

			return View::make('ff-actions-calc::rule.create', compact('signals'));
		}

		$oRequestData = Input::all();
		if (isset($oRequestData['flag_active'])) {
			$oRequestData['flag_active'] = ($oRequestData['flag_active'] == 'on') ? 1 : 0;
		}
		$oRequestData['terminal_id'] = $this->iTerminalId;

		$validator = Validator::make($oRequestData, Validators::getRuleValidators());

		if ($validator->fails()) {
			return json_encode(['status' => 'error', 'errors' => $validator->errors()]);
		}

		$oEvent = Rule::create($oRequestData);

		if (!$oEvent->push()) {
			return json_encode(['status' => 'error', 'message' => 'Не удалось создать правило.']);
		}

		return json_encode(['status' => 'success', 'message' => 'Новое правило создано.']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function update($id)
	{
		/** @var Rule $oRule */
		$oRule = Rule::find($id);

		if (Request::isMethod('GET')) {
			return View::make('ff-actions-calc::rule.update', ['rule' => $oRule]);
		}

		// update process
		$oRequestData = Input::only('name', 'rule', 'event_id', 'signal_id');
		$validator = Validator::make($oRequestData, Validators::getRuleValidators());

		if ($validator->fails()) {
			return json_encode(['status' => 'error', 'errors' => $validator->errors()]);
		}

		$oRule->fill($oRequestData);

		if ($oRule->save()) {
			return json_encode(['status' => 'success', 'message' => 'Правило обновлено.', 'update' => $oRequestData]);
		}

		return json_encode(['status' => 'error', 'message' => 'Не удалось обновить событие.']);
	}

	/**
	 * Rule delete
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public function delete($id)
	{
		/** @var Rule $rule */
		$rule = Rule::find($id);

		if (is_null($rule)) {
			App::abort(401, 'Нет такого правила');
		}

		if ($rule->delete()) {
			$iRulesCount = $rule->count('id');

			return json_encode([
				'status'  => 'success',
				'message' => 'Событие удалено.',
				'data'    => ['count' => $iRulesCount]
			]);
		}

		return json_encode(['status' => 'error', 'message' => 'Не удалось удалить событие.']);
	}

}
