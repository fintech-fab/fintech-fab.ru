<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;

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
	 * @param $url URL файла меню
	 *
	 * @return bool Если меню успешно импортировано - true, иначе - false
	 */
	public static function importMenu($url)
	{
		if ($filename = self::downloadFile($url)) {
			//@TODO-kmarenov: Реализовать парсинг файла меню и загрузку данных в БД

			return true;
		}

		return false;
	}

	/**
	 * Скачивает файл в директорию временных файлов
	 *
	 * @param $url URL файла
	 *
	 * @return mixed Если файл успешно загружен - имя файла, иначе - false
	 */
	private static function downloadFile($url)
	{
		$filename = tempnam(sys_get_temp_dir(), 'dinner');

		if ($file_resource = fopen((string)$url, 'r')) {
			if (file_put_contents($filename, $file_resource)) {
				fclose($file_resource);
				return $filename;
			}
			fclose($file_resource);
		}

		return false;
	}

}