<?php

class CheckBrowserWidget extends CWidget
{
	/**
	 * @var string выводимое сообщение
	 */
	public $sMessage = "<strong>Внимание!</strong> Идентификация работает только в браузерах <strong>Chrome</strong> и <strong>Firefox</strong> последних версий.";
	/**
	 * @var string выводимое сообщение для мобильного телефона
	 */
	public $sMobileMessage = " <strong>Внимание!</strong> Идентификация работает только <strong>на компьютере</strong>, в браузерах Chrome и Firefox последних версий.";

	public $bShowBrowsersLink = true;

	public $aHtmlOptions = array();

	public function run()
	{
		$this->render('check_browser_widget');
	}
}


