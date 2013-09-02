<?php

class AlertWidget extends CWidget
{
	/**
	 * @var string выводимое сообщение
	 */
	public $message = "";

	public $htmlOptions = array();

	public function run()
	{
		$this->render('alert_widget');
	}
}


