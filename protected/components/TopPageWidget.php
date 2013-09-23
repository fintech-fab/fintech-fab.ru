<?php
/**
 * Class TopPageWidget
 */

class TopPageWidget extends CWidget
{

	public $show = false; // показывать ли на странице

	public function run()
	{
		if ($this->show) {
			$this->render('top_page');
		}
	}
}
