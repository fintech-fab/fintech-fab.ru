<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\Rules;
use FintechFab\ActionsCalc\Models\Rule;
use Input;

class RequestController extends Controller {

	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');
		$input['data'] = (array)json_decode($input['data']);

		//Получаем все проавила теминала по событию
		$rules = Rule::getRules($input['term'], $input['sid']);
		//Определяем соответсвующие запросу правила
		$fitRules = Rules::getFitRules($rules, $input['data']);
		dd($rules);
	}

} 