<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Rule;
use Validator;
use Input;
use View;
use Request;

class RuleController extends BaseController
{

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
		$validator = Validator::make($oRequestData, Validators::getRuleValidationRules());

		if ($validator->fails()) {
			return json_encode(['status' => 'error', 'errors' => $validator->errors()]);
		}

		$oRule->fill($oRequestData);

//		$oRule->name = $oRequestData['name'];
//		$oRule->event_sid = $oRequestData['event_sid'];

		if ($oRule->save()) {
			return json_encode(['status' => 'success', 'message' => 'Правило обновлено.', 'update' => $oRequestData]);
		}

		return json_encode(['status' => 'error', 'message' => 'Не удалось обновить событие.']);
	}

}
