<?php

namespace FintechFab\Models;


/**
 * Interface IGitHubModel
 *
 * @package FintechFab\Models
 */
interface IGitHubModel
{
	/**
	 * @return string  Возвращает имя ключевого поля таблицы БД (может быть 'id' или иным)
	 */
	public function getKeyName();

	/**
	 * @return string  Нужно для отображения на экране, с какими данными выполняются операции.
	 */
	public function getMyName();

	/**
	 * Проверка и заполнение данных для сохранения. Возвращает true, если разрешено сохранять (иначе false)
	 * @param $inData
	 *
	 * @return bool
	 */
	public function dataGitHub($inData);

	/**
	 * Проверка и заполнение данных для обновления. Возвращает true или false, разрешая обновлять или не разрешая.
	 * @param $inData
	 *
	 * @return bool
	 */
	public function updateFromGitHub($inData);

}