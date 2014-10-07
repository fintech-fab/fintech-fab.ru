<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;
use FintechFab\Models\DinnerMenuItem;
use FintechFab\Models\DinnerMenuSection;
use Response;

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
	 * @param string $date Дата, на которую нужно вернуть блюда
	 *
	 * @return string Блюда в формате JSON
	 */
	public function getMenuItemsByDate($date)
	{
		// так будет отправлен специальный заголовок content-type
		// и браузер поймет что это json
		return Response::json(
			DinnerMenuItem::where('date', '=', $date)->get()->all()
		);
	}

	/**
	 * Возвращает все разделы меню
	 *
	 * @return string Разделы в формате JSON
	 */
	public function getMenuSections()
	{
		return Response::json(
			DinnerMenuSection::all()
		);
	}
}