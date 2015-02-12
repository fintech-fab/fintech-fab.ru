<?php
/**
 * @var            $content
 * @var Controller $this
 */

Yii::app()->bootstrap->registerBootstrapCss();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="language" content="Russian" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="КРЕДДИ - сколько взял – столько вернул!" />
	<meta name="keywords" content="" />
	<meta name="author" content="деньги, наличные, электронные деньги, срочно нужны, где взять, взаймы, быстрые, микрофинансовая организация, кредит, долг, вдолг, потребительские, денежный, частный, беспроцентный, ссуда, за час, кредитование, без справок, доход, срочный, экспресс, проценты, до зарплаты, неотложные, по паспорту, под расписку, выгодный, кредитные карты, кредитные системы, кредитные организации, кредитные истории, занять, краткосрочные, физическим лицам" />

	<title>Кредди - Сервис в твоем формате</title>
	<!-- Bootstrap -->
	<link href="static/kreddyline/css/bootstrap-responsive.css" rel="stylesheet">
	<!-- add style -->
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/bootstrap-overload.css?v=6" />
	<link href="static/kreddyline/css/style.css?v=12" rel="stylesheet">
	<link href="static/css/carousel.css?v=1.css" rel="stylesheet">

	<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCoreScript('maskedinput');
	Yii::app()->clientScript->registerCoreScript('yiiactiveform');
	?>

	<script src="static/kreddyline/js/main.js?v=1"></script>

	<script type="text/javascript" src="<?= Yii::app()->request->baseUrl; ?>/static/js/main.js?v=5"></script>


	<link rel="shortcut icon" href="<?= Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
</head>
<body>
<div class="soc_icon">
	<div id="fixed">
		<ul>
			<li class="soc_icon1">
				<a href="https://vk.com/kreddyru" target="_blank"><img src="static/kreddyline/images/soc_icon1.png" alt=""></a>
			</li>
			<li class="soc_icon2">
				<a href="https://www.facebook.com/pages/%D0%9A%D1%80%D0%B5%D0%B4%D0%B4%D0%B8-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D0%B2-%D1%82%D0%B2%D0%BE%D0%B5%D0%BC-%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%82%D0%B5/560011590772925?fref=ts" target="_blank"><img src="static/kreddyline/images/soc_icon2.png" alt=""></a>
			</li>
			<li class="soc_icon3">
				<a href="http://www.odnoklassniki.ru/group/53026435498223" target="_blank"><img src="static/kreddyline/images/soc_icon3.png" alt=""></a>
			</li>
		</ul>
	</div>
</div>
<!--===============================header===============================-->
<header>
	<!--  header navbar start -->
	<?php $this->renderPartial('//layouts/main_navbar_top'); ?>

	<!--/header-top-->    <!--big-foto-->    <!--div class="big-foto">
		<!--div class="extra-style1 hidden-phone">
			<div class="extra-style2">
				<div class="extra-style3" style="width: 1234px; height: 450px; background: url(/static/img/landing/7.png ) -150px 0;"></div>
			</div>
		</div>

	</div-->
	<?php
	$this->widget('application.components.CarouselWidget');
	?>
	<!--/big-foto-->
</header>
<!--===============================content==============================-->
<section id="content">
	<?= $content ?>
</section>
<!--===============================footer===============================-->
<div class="footer-border">
	<footer class="container">
		<nav>
			<?php
			$this->widget('FooterLinksWidget');
			?>
		</nav>
		<p>
			&copy; 2011-2014 ООО "Арбитр-Факторинг", 115172, г. Москва, Гончарная наб., 1, стр.4</p>
	</footer>
</div>

<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'modal-frame'));
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"></h4>
		</div>
		<div class="modal-body">
			<iframe style="width: 100%;height: 450px; border: 0;"></iframe>
		</div>
		<div class="modal-footer">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label'       => 'Закрыть',
				'url'         => '#',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));
			?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>

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

<?php $this->widget('PiwikWidget', array('iSiteId' => 1)); ?>

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