<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use FintechFab\ActionsCalc\Components\Events;
use FintechFab\ActionsCalc\Components\RulesHandler;
use FintechFab\ActionsCalc\Models\Rule;
use Input;

class RequestController extends Controller {

	public function getRequest(){
		$input = Input::only('term', 'sid', 'data', 'signal');
		$data = (array)json_decode($input['data']);

		//Записываем событие в базу
		Events::newEvent($input['term'], $input['sid'], $data);

		//Получаем все проавила теминала по событию
		$rules = Rule::getRules($input['term'], $input['sid']);

		//Определяем соответсвующие запросу правила
		$rulesHandler = new RulesHandler();
		$fitRules = $rulesHandler->getFitRules($rules, $data);
		if (count($fitRules) == 0) {
			die;
		}
		dd($fitRules);
	}

} 