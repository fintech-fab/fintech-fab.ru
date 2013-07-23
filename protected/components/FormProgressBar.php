<?php

class FormProgressBar extends CWidget
{
	public $startFilledFields; // значение уже заполненных полей progressbar'a
	public $options; // параметры выводимого progressbar'a
	public $htmlOptions; // html-параметры выводимого progressbar'a
	public $model; // модель, из которой будут взяты атрибуты

	public function run()
	{
		$this->render('progressbar');
	}
}
?>