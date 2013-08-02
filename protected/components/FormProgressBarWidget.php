<?php

class FormProgressBarWidget extends CWidget
{
	public $allFields = 17; // общее число требуемых для заполнения полей
	public $filledFields; // значение уже заполненных полей progressbar'a
	public $options; // параметры выводимого progressbar'a
	public $htmlOptions; // html-параметры выводимого progressbar'a
	public $model; // модель, из которой будут взяты атрибуты

	public function run()
	{
		$this->render('progress_bar');
	}
}
?>
