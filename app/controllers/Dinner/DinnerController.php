<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;
use FintechFab\Models\DinnerMenuItem;
use FintechFab\Models\DinnerMenuSection;

class DinnerController extends BaseController
{

	public $layout = 'dinner';

	/**
	 * Показывает страницу /dinner
	 *
	 * @return $this|\Illuminate\View\View
	 */
	public function dinner()
	{
		return $this->make('dinner');
	}

	/**
	 * Возвращает блюда на заданную дату
	 *
	 * @param $date Дата, на которую нужно вернуть блюда
	 *
	 * @return string Блюда в формате JSON
	 */
	public function getMenuItemsByDate($date)
	{
		return DinnerMenuItem::where('date', '=', $date)->get()->toJson();
	}

	/**
	 * Возвращает все разделы меню
	 *
	 * @return string Разделы в формате JSON
	 */
	public function getMenuSections()
	{
		return DinnerMenuSection::all()->toJson();
	}
}