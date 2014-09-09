<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;
use FintechFab\Models\DinnerMenuItem;
use FintechFab\Models\User;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

// устраняем все ошибки IDE. Сейчас она ругается на то, что
// Maatwebsite\Excel\Facades - такого неймспейса нет
// на самом деле все может отлично работать
// но обязательно нужно заставить IDE понять, что тут ошибки нет
// здесь проблема вероятно, в том, что ide-helper не знает про эту библиотеку и не подсказывает

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
		// начинаем менять сознание :-)
		/*

		не надо так:

		if(){
			if(){
				if(){

				}
			}
		}

		надо так:

		if(что-то не так и дальше работать не выйдет){
			выходим
		}

		тут что-то кодим

		if(что-то еще не так и дальше тоже не можем){
			выходим
		}

		тут что-то кодим

		*/

		// здесь "плохой тон"
		// сначала $file_name = self::downloadFile($url)
		// потом if($file_name)
		// нельзя внутри if совершать операции присваивания и вычисления
		// сначала в отдельные переменные,
		// потом внутри if использовать переменные

		if ($file_name = self::downloadFile($url)) {
			// без нужды closure не надо использовать
			// в данном случае нужды нет.
			// потому что при таком подходе невозможно разделить
			// на отдельные методы (если захочется)
			// код внутри этой функции
			// и придется делать use если захочется передать какую-нибудь
			// переменную в область видимость функци...
			// короче осторожно с этим
			Excel::load($file_name, function ($reader) {
				$reader->noHeading();
				$reader->ignoreEmpty();

				$arr_reader = $reader->toArray();

				$dates = array();

				foreach ($arr_reader as $sheet) {

					// пишем коменты над кодом, а не справа
					// код, код, код // неправильный комент

					// правильный комент
					// код, код, код

					$date_row = $sheet[0]; //берем первую строку листа, чтобы извлечь дату

					// хвалю за комментарий! их надо писать обязательно, особенно в сложных кусках кода
					//Если первая строка листа содержит дату, то делаем все блюда листа доступными в этот день
					//иначе - блюда с листа доступны во все даты, собранные с предыдущих листов
					// здесь у меня есть сомнение, что вообще надо думать о доступности блюд в датах.
					// главное чтобы соответствующее меню было импортировано на правильный день.
					// а доступность блюд для выбора пользователем - это уже будет решать логика на сайте (а не в импорте!)
					// короче, импорт не должен думать, он должен просто сохранить (или обновить) данные в базе
					// даже если скачан сильно старый файл с меню, ну и что? загрузили по датам и все.
					if (preg_match('/\d+\.\d+/', $date_row[1], $matches) > 0) {

						// можно пока не делать, но учесть переход на другой год стоило бы.
						// хотя, с учетом январского беспредела, может и не нужно учитывать.
						$current_date = date("Y-m-d", strtotime($matches[0] . '.' . date("Y"))); //год берем текущий
						$dates[] = $current_date; //добавляем текущую дату в массив дат

						self::importSheet($sheet, $current_date); //импортируем лист в БД
					} else //блюда с последнего листа - доступны в любой день
					{
						foreach ($dates as $date) //блюдо будет доступно во все даты, которые были в предыдущих листах
						{
							self::importSheet($sheet, $date); //импортируем лист в БД
						}
					}
				}
			});

			unlink($file_name);
			return true;
		}

		return false;
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
		// file_get_contents может не работать на каких-то системах,
		// т.к. не считается безопасной загрузкой контента.
		// взамен можно написать небольшую (свою) функцию по получению контента файла через curl
		if ($file_content = file_get_contents($url)) {
			// так просто на будущее.
			// если бы это был не парсинг обедов, а что нибудь посерьезнее
			// например загрузка каталога товаров в интернет-магазин
			// лучше бы куда нибудь сохранять загруженный файл, для истории,
			// чтобы потом можно было бы разобраться, что загрузили.
			$file_name = sys_get_temp_dir() . '/fintechfab_dinner_menu.xls';
			if (file_put_contents($file_name, $file_content)) {
				return $file_name;
			}
			unlink($file_name);
		}

		return false;
	}

	/**
	 * Формирует массив полей для передачи в модель DinnerMenuItem
	 *
	 * @param $row_items array Ячейки строки из excel-файла
	 * @param $date string Дата, когда блюдо будет доступно для заказа
	 *
	 * @return array|bool Массив полей, в случае неудачи - false
	 */
	private static function getMenuItemFields($row_items, $date)
	{
		if (!empty($row_items[1]) && !empty($row_items[2])) //Если первые две ячейки в строке не пусты - значит в этой строке блюдо
		{
			$fields = [
				'title' => $row_items[1],
				'price' => $row_items[2],
				'date' => $date,
			];

			if (!empty($row_items[3])) $fields['description'] = $row_items[3]; //Описания может не быть

			return $fields;
		}

		return false;
	}

	/**
	 * Импортирует лист из excel-файла в БД
	 *
	 * @param array $sheet Лист
	 * @param string $date Дата, когда блюдо будет доступно для заказа
	 */
	private static function importSheet($sheet, $date)
	{
		foreach ($sheet as $row) {
			// ааааа! фигурные скобки пропущены! страшное преступление!
			// карается отрывом рук на расстояние 1 метр от тела :-)
			// скобки всегда использовать, всегда-всегда.
			if ($fields = self::getMenuItemFields($row, $date)) //формируем массив полей для модели
				DinnerMenuItem::create($fields); //добавляем блюдо в БД
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
		//Получаем пользователей с ролью employee
		// круто было бы так сделать:
		// $role = тут объект роли employee
		// $users = $role->users();
		// т.е. не "было бы круто", а "надо".
		$users = User::with(array('roles' => function ($query) {
				$query->where('role', 'like', 'employee');
			}))->get();

		//Рассылаем напоминания всем найденным пользователям
		foreach ($users as $user) {
			Mail::send('emails.dinner', array(), function ($message) use ($user) {
				$message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Вы можете заказать обед');
			});
		}
	}

}