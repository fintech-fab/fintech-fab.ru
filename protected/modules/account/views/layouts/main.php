<?php /* @var $this Controller */
/* @var $content */

Yii::app()->bootstrap->registerBootstrapCss();
?>
<!DOCTYPE html>
<html lang="en">

<head><!-- head start -->
	<?php $this->beginContent('//layouts/main_head');
	echo $content;
	$this->endContent();
	?>
	<!-- head end -->
</head>

<body class="home">
<!--  header navbar start -->
<?php $this->beginContent('/layouts/main_navbar_top');
echo $content;
$this->endContent();
?>
<!--  header navbar end -->
<?php
//<div class="left-stars-23feb"></div>
//<div class="right-stars-23feb"></div>
?>
<!-- main content start--><!--<div class="main-bgr-8mar">--><br />
<?= $content; ?>
<br />
<!--</div>--><!-- main content end--><!-- footer start-->
<?php $this->beginContent('//layouts/main_footer');
echo $content;
$this->endContent();
?>
<!-- footer start--><!-- Piwik -->
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
<noscript><p><img src="http://metric.kreddy.ru/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
</body>
</html>
