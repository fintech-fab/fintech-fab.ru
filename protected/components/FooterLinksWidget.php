<?php

class FooterLinksWidget extends CWidget
{
	public $links;

	public function run()
	{
		$this->render('footerlinks');
	}
}
?>