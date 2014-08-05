<?php

namespace FintechFab\Models;


/**
 * Interface IGitHubModel
 *
 * @package FintechFab\Models
 */
interface IGitHubModel
{
	public function getKeyName(); //Возвращает имя ключевого поля таблицы БД (может быть 'id' или иным).
	public function getMyName();  //Нужно для отображения на экране, с какими данными выполняются операции.

	//Проверка и заполнение данных для сохранения. Возвращает true, если разрешено сохранять (иначе false)
	public function dataGitHub($inData);
	//Проверка и заполнение данных для обновления. Возвращает true или false, разрешая обновлять или не разрешая.
	public function updateFromGitHub($inData);

}