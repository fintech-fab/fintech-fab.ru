<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="КРЕДДИ - ты всегда при деньгах. Как просто получить и вернуть заём? Простая и удобная услуга займов. Сколько взял – столько вернул!" />
	<meta name="keywords" content="" />
	<meta name="author" content="деньги, наличные, электронные деньги, срочно нужны, где взять, взаймы, займу, быстрые, в займы, займ, заём, займы, микрофинансовая организация, кредит, долг, вдолг, потребительские, денежный, частный, беспроцентный, ссуда, за час, кредитование, без справок, доход, срочный, экспресс, проценты, до зарплаты, неотложные, по паспорту, под расписку, выгодный, кредитные карты, кредитные системы, кредитные организации, кредитные истории, занять, краткосрочные, физическим лицам" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-overload.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />


	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />

	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/style.css" type="text/css" />

	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/reset.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/payment.css" type="text/css" />

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/main.js"></script>

	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
	<?php
	Yii::app()->bootstrap->registerCoreCss();
	Yii::app()->bootstrap->registerYiiCss();
	Yii::app()->bootstrap->registerCoreScripts();
	?>

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />
</head>
<!-- ClickTale Bottom part -->
<div id="ClickTaleDiv" style="display: none;"></div>
<script type="text/javascript">
	if (document.location.protocol != 'https:')
		document.write(unescape("%3Cscript%20src='http://s.clicktale.net/WRe0.js'%20type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	if (typeof ClickTale == 'function') ClickTale(7143, 1, "www08");
</script>
<!-- ClickTale end of Bottom part -->

<body class="home">

<!-- ClickTale Top part -->
<script type="text/javascript">
	var WRInitTime = (new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->

<nav class="navbar navbar-top">
	<div class="navbar-inner">
		<div class="container">

			<!-- Special image
			<div class="new-year-left" style="margin: 5px 0px -145px -120px; float: left; background: url('<php echo Yii::app()->request->baseUrl; ?>/static/img/lenta9may.png') no-repeat; height: 140px; width: 112px"></div>
			-->

			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a>
			<a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/logo-slogan.png" alt="Kreddy"></a>

			<span class="hotline pull-right"><small>Горячая линия</small> 8 800 555-75-78 <small>(бесплатно по России)
				</small></span>
		</div>
	</div>
</nav>

<!--div class="page-divider1"></div-->

<?php
$this->widget('TopPageWidget', array("show" => $this->showTopPageWidget));
?>
<?php echo $content; ?>
<br />

<div class="page-divider1"></div>

<div class="container">
	<div class="row">
		<section class="span12">
			<h2>Узнай больше о нас!</h2>

			<p class="intro">Если возникнут вопросы, позвони нам, или <a href="mailto:info@kreddy.ru">напиши</a></p>
			<?php
			$this->widget('BottomTabsWidget', array(
				'tabs' => Tabs::model()->findAll(array('order' => 'tab_order')),
			));
			?>
		</section>
	</div>
</div>

<div class="page-divider"></div>

<div class="container">
	<div class="row">
		<section class="span12">
			<footer>
				<?php
				$this->widget('FooterLinksWidget', array(
					'links' => FooterLinks::model()->findAll(array('order' => 'link_order')),
				));
				?>
				<p>&copy; 2012 ООО "Финансовые Решения"</p>
			</footer>
		</section>
	</div>
</div>
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
					webvisor: true,
					clickmap: true,
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
