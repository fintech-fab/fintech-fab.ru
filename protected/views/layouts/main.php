<?php
/* @var $this Controller */
/* @var $content */

Yii::app()->bootstrap->registerBootstrapCss();
?>
<!DOCTYPE html>
<html lang="ru">
<head><!-- head start -->
	<?php $this->beginContent('//layouts/main_head');
	echo $content;
	$this->endContent();
	?>
</head>
<!-- head end -->

<body class="home">
<!--  header navbar start -->
<?php $this->beginContent('//layouts/main_navbar_top');
echo $content;
$this->endContent();
?>
<!--  header navbar end -->
<div class="top-page-widget">
	<?php
	$this->widget('TopPageWidget', array("show" => $this->showTopPageWidget));
	?>
</div>
<?php
//<div class="left-stars-23feb"></div>
//<div class="right-stars-23feb"></div>
?>
<!--<div class="main-bgr-8mar">--><!-- main content start-->
<?= $content; ?>
<!-- main content end--><br />

<div class="page-divider1"></div>

<div class="container">
	<div class="row">
		<h2 class="learn-more">Узнай больше о нас!</h2>

		<p class="intro learn-more">Возникли вопросы? <?php echo CHtml::link('Посмотри ответы', Yii::app()
				->createUrl('site/faq')); ?>! </p>
	</div>

	<div class="row" style="margin-left: 0;">
		<?php
		$this->widget('BottomTabsWidget');
		?>
	</div>

	<?php if (!SiteParams::getIsIvanovoSite()) : ?>
		<br /><br />
		<div class="row">

			<p class="intro">
				Со всей информацией, касающейся финансовой ответственности Клиента в случае просрочки платежа (штрафы и
				пени), методах взыскания задолженности ООО "Финансовые Решения", а так же условий возобновления займа,
				можно ознакомиться в Оферте по
				<a href="#" class="dotted" onclick="return doOpenModalFrame('/footerLinks/view/offer_kreddyline', 'Оферта на дистанционный займ');">ссылке</a>
			</p>
		</div>
	<?php endif ?>
</div>
<!--</div>--><!-- footer start-->
<?php $this->beginContent('//layouts/main_footer');
echo $content;
$this->endContent();
?>
<!-- footer end-->
<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-30199735-1']);
	_gaq.push(['_trackPageview']);

	(function () {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();

</script>

<script type="text/javascript">
	var yaParams = {/*Здесь параметры визита*/};
</script>

<script type="text/javascript">
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function () {
			try {
				w.yaCounter21390544 = new Ya.Metrika({id: 21390544,
					trackLinks: true,
					accurateTrackBounce: true,
					trackHash: true, params: window.yaParams || { }});
			} catch (e) {
			}
		});

		var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () {
				n.parentNode.insertBefore(s, n);
			};
		s.type = "text/javascript";
		s.async = true;
		s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else {
			f();
		}
	})(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
	<div><img src="//mc.yandex.ru/watch/21390544" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>

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
<noscript><p><img src="http://metric.kreddy.ru/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
	(function () {
		var widget_id = '65726';
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.async = true;
		s.src = '//code.jivosite.com/script/widget/' + widget_id;
		var ss = document.getElementsByTagName('script')[0];
		ss.parentNode.insertBefore(s, ss);
	})();
</script>
<!-- {/literal} END JIVOSITE CODE -->

</body>
</html>
