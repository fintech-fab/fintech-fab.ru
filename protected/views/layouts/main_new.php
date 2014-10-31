<?php
/**
 * @var                $content
 * @var FormController $this
 */
$this->pageTitle = 'Кредди - Сервис в твоем формате';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= CHtml::encode($this->pageTitle); ?></title>
	<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
	//Yii::app()->clientScript->registerCoreScript('maskedinput');
	Yii::app()->clientScript->registerCoreScript('yiiactiveform');
	?>
	<!--script type="text/javascript" src="/static/newmain/js/bootstrap.min.js"></script-->
	<script type="text/javascript" src="/static/newmain/js/TweenMax.min.js"></script>
	<script type="text/javascript" src="/static/newmain/js/jquery.gsap.min.js"></script>
	<script type="text/javascript" src="<?= Yii::app()->request->baseUrl; ?>/static/js/main.js?v=5"></script>

	<!-- Bootstrap -->
	<link href="/static/newmain/css/bootstrap.min.css" type="text/css" media="screen" rel="stylesheet">

	<!--<link rel="stylesheet" type="text/css" href="css/jquery.tzineClock.css" />-->
	<!--<script type="text/javascript" src="js/jquery.tzineClock.js"></script>-->

	<style>
		@font-face {
			font-family: 'MyriadWebPro';
			src: url('/static/newmain/fonts/MyriadWebPro.ttf');
			src: url('/static/newmain/fonts/MyriadWebPro.eot?#iefix') format('embedded-opentype'), url('/static/newmain/fonts/MyriadWebPro.woff') format('woff'), url('/static/newmain/fonts/MyriadWebPro.ttf') format('truetype'), url('/static/newmain/fonts/MyriadWebPro.svg#MyriadWebPro') format('svg');
			font-weight: normal;
			font-style: normal;
		}
	</style>
	<link rel="stylesheet" href="/static/newmain/css/general_style.css?v=2" type="text/css" media="screen">
	<script type="text/javascript" src="/static/newmain/js/main.js"></script>

	<link rel="shortcut icon" href="<?= Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />

	<!--[if lt IE 9]>
		<!--<script type="text/javascript" src="js/html5.js"></script>-->    <![endif]-->
</head>
<body>

<div id="fb-root"></div>
<!--script>(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script-->

<div id="header_wrap">
	<div id="header" class="container">
		<div class="row">
			<div class="col-sm-8 col-xs-12">
				<div class="row header_leftblock">
					<div class="col-md-6 col-xs-12 headlb_left">
						<a href="<?= Yii::app()->createAbsoluteUrl('/form'); ?>" class="up_logo">
							<img src="/static/newmain/images/index_logo.png" /> </a>
					</div>
					<div class="col-md-4 col-xs-12 headlb_right">

						<?php
						$this->widget('UserCityWidget3');
						?>
					</div>
					<div class="col-md-2 col-xs-12 headlb_right">
						<div class="login_block">
							<a href="<?= Yii::app()->createUrl('/account/'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Войти</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12 headr_rightblock">
				<div class="headrbl">
					<p class="up_phone">8 800 555-75-78</p>

					<p class="up_phonetext">По будням с 9:00 до 18:00 по мск</p>

					<div class="up_line"></div>
					<div class="up_socico">
						<a href="https://vk.com/kreddyru" class="up_si usi_vk">
							<img src="/static/newmain/images/11.png" /> </a>
						<a href="https://www.facebook.com/pages/%D0%9A%D1%80%D0%B5%D0%B4%D0%B4%D0%B8-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D0%B2-%D1%82%D0%B2%D0%BE%D0%B5%D0%BC-%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%82%D0%B5/560011590772925?fref=ts" class="up_si usi_f">
							<img src="/static/newmain/images/10.png" /> </a>
						<a href="http://www.odnoklassniki.ru/group/53026435498223" class="up_si usi_o">
							<img src="/static/newmain/images/9.png" /> </a>
						<!--a href="#" class="up_si usi_i"> <img src="/static/newmain/images/8.png" /> </a-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--===============================content==============================-->
<section id="content">
	<?= $content ?>
</section>
<!--===============================footer===============================-->


<div class="page_separator"></div>

<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center footer_hrefs">
				<?php
				$this->widget('FooterLinksWidget');
				?>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-8 col-sm-offset-2 col-xs-offset-12 col-xs-offset-0 text-center footer_text">
				<p>Микрофинансовая организация общество с ограниченной ответственностью “Финансовые Решения”
					Регистрационный номер записи в государственном реестре МФО №2110177000213 от 19 июля 2011 г. ОГРН
					1117746371270<br> г. Москва, Гончарная наб. 1, стр. 4<br> тел. 8-800-555-75-78</p>
			</div>
		</div>
	</div>
</div>


<div id="modal-frame" class="modal fade">
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
					'type'        => 'warning',
					'url'         => '#',
					'htmlOptions' => array('data-dismiss' => 'modal'),
				));
				?>
			</div>
		</div>
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

</body>
</html>
