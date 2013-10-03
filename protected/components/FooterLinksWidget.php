<?php

/**
 * Class FooterLinksWidget
 */

class FooterLinksWidget extends CWidget
{
	public $links;

	public function run()
	{
		$this->links = FooterLinks::getAllLinks();


		if (!empty($this->links)) {
			$this->render('footer_links');
		}
	}
}