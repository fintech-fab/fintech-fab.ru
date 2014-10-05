<?php

namespace FintechFab\Components;

use Excel;
use File;
use FintechFab\Models\DinnerMenuItem;
use FintechFab\Models\DinnerMenuSection;

class DinnerImportMenu
{
	/**
	 * Импортирует файл меню в базу данных
	 *
	 * @param $url string URL файла меню
	 *
	 * @return array Статус импорта и сообщение статуса
	 */
	public static function importMenu($url)
	{
		$file_name = urldecode(basename($url));

		$file_path = storage_path() . '/dinner/' . $file_name;

		if (File::exists($file_path)) {
			return ['status' => 'ok', 'message' => 'Файл ' . $file_name . ' был загружен ранее'];
		}

		$download_status = self::downloadFile($url);

		if ($download_status !== true) {
			return ['status' => 'error', 'message' => $download_status];
		}

		$reader = Excel::load($file_path);

		if (!$reader) {
			return ['status' => 'error', 'message' => 'Не удалось прочитать файл ' . $file_name];
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

		return ['status' => 'ok', 'message' => 'Файл ' . $file_name . ' успешно импортирован'];
	}

	/**
	 * Скачивает файл в директорию /storage/dinner
	 *
	 * @param $url string URL файла
	 *
	 * @return string|bool Если файл успешно загружен - true, иначе - текст ошибки
	 */
	private static function downloadFile($url)
	{
		$curl_session = curl_init($url);

		if (!$curl_session) {
			return "Не удалось открыть сессию curl";
		}

		curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_session, CURLOPT_BINARYTRANSFER, true);

		$file_content = curl_exec($curl_session);

		curl_close($curl_session);

		if (!$file_content) {
			return "Не удалось скачать файл";
		}

		$path = storage_path() . '/dinner';

		if (!File::exists($path)) {
			File::makeDirectory($path);
		}

		$file_name = $path . '/' . urldecode(basename($url));

		$is_file_saved = file_put_contents($file_name, $file_content);

		if (!$is_file_saved) {
			return "Не удалось сохранить файл";
		}

		return true;

	}

	/**
	 * Импортирует лист из excel-файла в БД
	 *
	 * @param array  $sheet Лист
	 * @param string $date  Дата, когда блюдо будет доступно для заказа
	 */
	private static function importSheet($sheet, $date)
	{
		$section_id = 0;
		foreach ($sheet as $row) {
			//если вторая ячейка строки пуста, а первая не содержит дату, то это категория блюда
			if (empty($row[2]) && !preg_match('/\d+\.\d+/', $row[1], $matches)) {
				$section_id = DinnerMenuSection::create(['title' => $row[1]])->id;
			} else {
				//формируем массив полей для модели блюда
				$fields = self::getMenuItemFields($row, $date, $section_id);

				if ($fields) {
					//добавляем блюдо в БД
					DinnerMenuItem::create($fields);
				}
			}
		}
	}

	/**
	 * Формирует массив полей для передачи в модель DinnerMenuItem
	 *
	 * @param $row_items array Ячейки строки из excel-файла
	 * @param $date      string Дата, когда блюдо будет доступно для заказа
	 *
	 * @return array|bool Массив полей, в случае неудачи - false
	 */
	private static function getMenuItemFields($row_items, $date, $section_id)
	{
		//Если какая-то из первых двух ячееек в строке пуста - значит в этой строке не блюдо
		if (empty($row_items[1]) || empty($row_items[2])) {
			return false;
		}

		$fields = [
			'title' => $row_items[1],
			'price' => $row_items[2],
			'date'  => $date,
			'section_id' => $section_id,
		];

		//Описания может не быть
		if (!empty($row_items[3])) {
			$fields['description'] = $row_items[3];
		}

		return $fields;
	}

	/**
	 * Генерирует url файла с меню по номеру недели, начиная с текущей
	 *
	 * @param int $week Для какой недели, если считатать от текущей, формировать url файла. По умолчанию 0 - текущая неделя
	 *
	 * @return string Сгенерированный url файла
	 */
	public static function getFileUrlByWeek($week = 0)
	{
		//Поиск даты понедельника для имени файла
		//Если сегодня понедельник, берем сегодняшнюю дату и прибавляем необходимое количество недель
		if (date("l") == "Monday") {
			$monday = date("d.m", time() + $week * 60 * 60 * 24 * 7);
		} //Если сегодня не понедельник, продолжаем искать
		else {
			//Если сегодня суббота или воскресенье,
			//ищем следующий понедельник и прибавляем необходимое количество недель
			if (date("l") == "Sunday" || date("l") == "Saturday") {
				$monday = date("d.m", strtotime("next Monday +" . $week . " week"));
			}
			//Если сегодня не суббота и не воскресенье,
			//ищем предыдущий понедельник и прибавляем необходимое количество недель
			else {
				$monday = date("d.m", strtotime("last Monday +" . $week . " week"));
			}
		}

		//Поиск даты пятницы для имени файла
		//Если сегодня пятница, берем сегодняшнюю дату и прибавляем необходимое количество недель
		if (date("l") == "Friday") {
			$friday = date("d.m", time() + $week * 60 * 60 * 24 * 7);
		} //Если сегодня не пятница, ищем следующую пятницу и прибавляем необходимое количество недель
		else {
			$friday = date("d.m", strtotime("next Friday +" . $week . " week"));
		}

		//Составляем имя файла из найденных дат
		$filename = rawurlencode($monday . " - " . $friday) . '.xls';

		//Составляем url файла
		$url = 'http://www.obedvofis.info/images/files_menu/' . $filename;

		return $url;
	}
} 