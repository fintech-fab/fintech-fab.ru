<?php

class FormComponent
{
	/**
	 * Возвращает модель текущей формы.
	 *
	 * @return ClientCreateFormAbstract
	 */
	public function getFormModel()
	{
		return;
	}

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * Если да, выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function ajaxValidation()
	{
		return;
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|null
	 */
	public function getPostData()
	{
		return;
	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract $model
	 */
	public function formDataProcess($model)
	{

	}

	/**
	 * Возвращает название необходимого для генерации представления.
	 *
	 * @return string
	 */
	public function getView()
	{
		return;
	}
}
