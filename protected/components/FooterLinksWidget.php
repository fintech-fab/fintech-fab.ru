<?php

/**
 * Class FooterLinksWidget
 */

class FooterLinksWidget extends CWidget
{
	public $links;

	public function run()
	{

		$this->links = FooterLinks::model()->cache(60)->findAll(array('order' => 'link_order'));


		if (!empty($this->links)) {
			$this->render('footer_links');
		}
	}
}