<?php

class CheckBrowserWidget extends CWidget
{
	/**
	 * @var string выводимое сообщение
	 */
	//TODO переделать виджет, а то одно сообщение тут, второе - в browser.js
	public $sMessage = "<strong>Внимание!</strong> Во время заполнения анкеты Вы можете пройти
видеоидентификацию, которая работает только в браузерах <strong>Chrome</strong> и <strong>Firefox</strong>
последних версий.";

	public $aHtmlOptions = array();

	public function run()
	{
		$this->render('check_browser_widget');
	}
}

?>
