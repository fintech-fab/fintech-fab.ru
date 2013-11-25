<?php
/* @var $this Controller */
/* @var $content */
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
<?php
$this->widget('TopPageWidget', array("show" => $this->showTopPageWidget));
?>
<!-- main content start-->
<?= $content; ?>
<!-- main content end--><br />

<div class="page-divider1"></div>

<div class="container">
	<div class="row">
		<h2 class="learn-more">Узнай больше о нас!</h2>

		<p class="intro learn-more">Возникли вопросы? <?php echo CHtml::link('Посмотрите ответы', Yii::app()
				->createUrl('site/faq')); ?>! </p>
	</div>

	<div class="row" style="margin-left: 0;">
		<?php
		$this->widget('BottomTabsWidget');
		?>
	</div>
</div>
<!-- footer start-->
<?php $this->beginContent('//layouts/main_footer');
echo $content;
$this->endContent();
?>
<!-- footer start-->
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
