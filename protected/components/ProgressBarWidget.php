<?php

class ProgressBarWidget extends CWidget
{
	public $startFilledFields; // значение уже заполненных полей progressbar'a
	public $options; // параметры выводимого progressbar'a
	public $htmlOptions; // html-параметры выводимого progressbar'a
	public $model; // модель, из которой будут взяты атрибуты

	public function run()
	{
		$this->render('progressbar',array(
			'startFilledFields'=>$this->startFilledFields,
			'options'=>$this->options,
			'htmlOptions'=>$this->htmlOptions,
			'model'=>$this->model,
		));
	}
}