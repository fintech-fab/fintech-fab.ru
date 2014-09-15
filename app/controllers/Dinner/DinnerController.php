<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;
use Excel;
use File;
use FintechFab\Models\DinnerMenuItem;
use FintechFab\Models\Role;
use FintechFab\Models\User;
use Mail;

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
	 * Импортирует файл меню в базу данных
	 *
	 * @param $url string URL файла меню
	 *
	 * @return bool Если меню успешно импортировано - true, иначе - false
	 */
	public static function importMenu($url)
	{
		$file_name = self::downloadFile($url);

		if (!$file_name) {
			return false;
		}

		$reader = Excel::load($file_name);

		if (!$reader) {
			return false;
		}

		$reader->noHeading();
		$reader->ignoreEmpty();

		$arr_reader = $reader->toArray();

		$dates = array();

		foreach ($arr_reader as $sheet) {

			//берем первую строку листа, чтобы извлечь дату
			$date_row = $sheet[0];

			//Если первая строка листа содержит дату, то проставляем ее блюдам с этого листа
			//иначе - блюдам с листа ставим все даты, собранные с предыдущих листов
			if (preg_match('/\d+\.\d+/', $date_row[1], $matches) > 0) {
				//год берем текущий
				$current_date = date("Y-m-d", strtotime($matches[0] . '.' . date("Y")));
				//добавляем текущую дату в массив дат
				$dates[] = $current_date;
				//импортируем лист в БД
				self::importSheet($sheet, $current_date);
			} //блюда с последнего листа - доступны во все дни из файла
			else {
				//блюда с листа сохраняем со всеми датами, которые были на предыдущих листах
				foreach ($dates as $date) {
					//импортируем лист в БД
					self::importSheet($sheet, $date);
				}
			}
		}

		return true;
	}

	/**
	 * Скачивает файл в директорию временных файлов
	 *
	 * @param $url string URL файла
	 *
	 * @return string|bool Если файл успешно загружен - имя файла, иначе - false
	 */
	private static function downloadFile($url)
	{
		$curl_session = curl_init($url);

		if (!$curl_session) {
			return false;
		}

		curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_session, CURLOPT_BINARYTRANSFER, true);

		$file_content = curl_exec($curl_session);

		curl_close($curl_session);

		if (!$file_content) {
			return false;
		}

		$path = storage_path() . '/dinner';

		if (!File::exists($path)) {
			File::makeDirectory($path);
		}

		$file_name = $path . '/menu_' . date('Y-m-d-U') . '.xls';

		$is_file_saved = file_put_contents($file_name, $file_content);

		if (!$is_file_saved) {
			return false;
		}

		return $file_name;

	}

	/**
	 * Формирует массив полей для передачи в модель DinnerMenuItem
	 *
	 * @param $row_items array Ячейки строки из excel-файла
	 * @param $date      string Дата, когда блюдо будет доступно для заказа
	 *
	 * @return array|bool Массив полей, в случае неудачи - false
	 */
	private static function getMenuItemFields($row_items, $date)
	{
		//Если какая-то из первых двух ячееек в строке пуста - значит в этой строке не блюдо
		if (empty($row_items[1]) || empty($row_items[2])) {
			return false;
		}

		$fields = [
			'title' => $row_items[1],
			'price' => $row_items[2],
			'date'  => $date,
		];

		//Описания может не быть
		if (!empty($row_items[3])) {
			$fields['description'] = $row_items[3];
		}

		return $fields;
	}

	/**
	 * Импортирует лист из excel-файла в БД
	 *
	 * @param array  $sheet Лист
	 * @param string $date  Дата, когда блюдо будет доступно для заказа
	 */
	private static function importSheet($sheet, $date)
	{
		foreach ($sheet as $row) {
			//формируем массив полей для модели
			$fields = self::getMenuItemFields($row, $date);

			if ($fields) {
				//добавляем блюдо в БД
				DinnerMenuItem::create($fields);
			}
		}
	}

	// ну не место в контроллере этому методу.
	// как писал уже где то - сделать компонент DinnerComponent и все делать там
	// а еще ImportDinnerComponent для импорта данных из файла
	// каждый класс должен решать строго свои задачи
	// универсальные классы - зло.
	/**
	 * Отправка писем - напоминаний
	 */
	public static function sendReminders()
	{
		$role = Role::where('role', 'like', 'employee')->first();
		$users = $role->users;

		//Рассылаем напоминания всем найденным пользователям
		foreach ($users as $user) {
			Mail::send('emails.dinner', array(), function ($message) use ($user) {
				$message->to($user->email, $user->first_name . ' ' . $user->last_name)
					->subject('Вы можете заказать обед');
			});
		}
	}

}