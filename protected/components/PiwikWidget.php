<?php

/**
 * Class PiwikWidget
 */
class PiwikWidget extends CWidget
{
	public $iSiteId = 1;

	public $sUrl = 'http://metric.kreddy.ru';

	public function run()
	{
		if(SiteParams::isLocalServer()){
			return;
		}

		?>
		<!-- Piwik -->
		<script type="text/javascript">
			var _paq = _paq || [];
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function () {
				var u = (("https:" == document.location.protocol) ? "https" : "http") + "://metric.kreddy.ru/piwik/";
				_paq.push(['setTrackerUrl', u + 'piwik.php']);
				_paq.push(['setSiteId', 1]);
				var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
				g.type = 'text/javascript';
				g.defer = true;
				g.async = true;
				g.src = u + 'piwik.js';
				s.parentNode.insertBefore(g, s);
			})();

		</script>
		<noscript><p><img src="<?= $this->sUrl ?>/piwik/piwik.php?idsite=<?= $this->iSiteId ?>" style="border:0;" alt="" /></p></noscript>
		<!-- End Piwik Code -->
	<?php

	}
}
