<?php

namespace FintechFab\ActionsCalc\Controllers;


use FintechFab\ActionsCalc\Components\AuthCheck;

class TablesController extends BaseController
{

	public $layout = 'account';

	/**Таблица правил
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableRules()
	{
		$terminal = AuthCheck::getTerm();

		$rules = $terminal->rules()->paginate(10);

		return $this->make('tableRule', array(
			'rules' => $rules,
		));
	}

	/**Таблица событий
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableEvents()
	{
		$terminal = AuthCheck::getTerm();

		$rules = $terminal->rules()->paginate(10);

		return $this->make('tableRule', array(
			'rules' => $rules,
		));
	}

	/**Таблица сигналов
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableSignals()
	{
		$terminal = AuthCheck::getTerm();

		$rules = $terminal->rules()->paginate(10);

		return $this->make('tableRule', array(
			'rules' => $rules,
		));
	}
} 