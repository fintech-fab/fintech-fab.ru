<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Models\Rule;
use Request;
use View;

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
		$oRule = Rule::find($id);

		if (Request::isMethod('GET')) {
			return View::make('ff-actions-calc::rule.update', ['rule' => $oRule]);
		}

		return json_encode([]);
	}

}
