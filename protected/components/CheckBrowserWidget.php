<?php

/**
 * Class CheckBrowserWidget
 */

class CheckBrowserWidget extends CWidget
{
	/**
	 * @var string выводимое сообщение
	 */
	public $sMessage = "<strong>Внимание!</strong> Во время заполнения анкеты Вы можете пройти видеоидентификацию, которая работает только в браузерах <strong>Chrome</strong> и <strong>Firefox</strong> последних версий.";
	/**
	 * @var string выводимое сообщение для мобильного телефона
	 */
	public $sMobileMessage = " <strong>Внимание!</strong> Во время заполнения анкеты Вы можете пройти видеоидентификацию. Видеоидентификация работает только <strong>на компьютере</strong>, в браузерах Chrome и Firefox последних версий.";

	public $aHtmlOptions = array();

	public function run()
	{
		$this->render('check_browser_widget');
	}
}


