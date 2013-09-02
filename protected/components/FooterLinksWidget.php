<?php

class FooterLinksWidget extends CWidget
{
	public $links;

	public function run()
	{
		if (!empty($this->links)) {
			$this->render('footer_links');
		}
	}
}