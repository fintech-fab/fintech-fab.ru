<?php

/**
 * Class FooterLinksWidget
 */

class FooterLinksWidget extends CWidget
{
	public $links;
	public $iSite = 1;

	public function run()
	{
		if(SiteParams::getIsIvanovoSite()) {
			$this->iSite = 2;
		}

		$this->links = FooterLinks::getSiteLinks($this->iSite);


		if (!empty($this->links)) {
			$this->render('footer_links');
		}
	}
}