<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use Validator;
use Input;
use View;
use App;

class SignalController extends BaseController
{
	/**
	 * Store a newly created resource in storage.
	 */
	public function store()
	{
		$aRequestData = Input::only('name', 'signal_sid');

		$aRequestData['terminal_id'] = $this->iTerminalId;

		$validator = Validator::make($aRequestData, Validators::getSignalValidator());

		if ($validator->fails()) {
			return ['status' => 'error', 'errors' => $validator->errors()];
		}

		$oSignal = Signal::create($aRequestData);
		$aReturnData = [];

		if (!$oSignal->push()) {
			return ['status' => 'error', 'message' => 'Не удалось создать сигнал'];
		}

		$aReturnData['id'] = $oSignal->id;
		$aReturnData['name'] = $aRequestData['name'];
		$aReturnData['signal_sid'] = $aRequestData['signal_sid'];

		return ['status' => 'success', 'message' => 'Сигнал успешно создан', 'data' => $aReturnData];
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$signal = Signal::find($id);

		return View::make('ff-actions-calc::signal.edit', compact('signal'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return array
	 */
	public function update($id)
	{
		/** @var Signal $oSignal */
		$oSignal = Signal::find($id);
		$aRequestData = Input::only('name', 'signal_sid');

		// validation
		$aValidatorRules = Validators::getSignalValidator();
		$aValidatorRules['signal_sid'] = $aValidatorRules['signal_sid'] . ',' . $id;
		$validator = Validator::make($aRequestData, $aValidatorRules);

		if ($validator->fails()) {
			return ['status' => 'error', 'errors' => $validator->errors()];
		}

		// filling and updating
		$oSignal->fill($aRequestData);

		if (!$oSignal->save()) {
			return ['status' => 'error', 'message' => 'Не удалось обновить сигнал'];
		}

		return [
			'status'     => 'success', 'data' => [
				'name'       => $oSignal->name,
				'signal_sid' => $oSignal->signal_sid
			], 'message' => "Сигнал \"$oSignal->name\" обновлён."
		];
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return string
	 */
	public function destroy($id)
	{
		/** @var Signal $oSignal */
		$oSignal = Signal::find($id);

		$oRules = Rule::where('signal_id', '=', $id)->first();

		if (!is_null($oRules)) {
			return ['status' => 'error', 'message' => 'Сигнал используется.'];
		}

		if (is_null($oSignal)) {
			App::abort(401, 'Нет такого правила');
		}

		if (!$oSignal->delete()) {
			return ['status' => 'error', 'message' => 'Не удалось удалить событие.'];
		}

		return ['status' => 'success', 'message' => 'Событие удалено.'];
	}

}
