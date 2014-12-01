	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FINTECH_FAB</title>
		<!-- FontAwesome -->
		<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
		<!-- Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Exo+2:400,700italic,700,200,200italic' rel='stylesheet'
		      type='text/css'>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<!-- The Styles -->
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<?= View::make('layouts.inc.head.head') ?>
	</head>
	<body id="top">

	<a href="#top" id="up" data-spy="affix" data-offset="100"><span class="fa fa-caret-up"></span></a>

	<nav class="navbar" role="navigation">

		<div class="navbar-header">
			<strong></strong>
		</div>

		<ul class="nav">
			<li><a href="index.html">Главная</a></li>
			<li><a href="features.html">Кто мы?</a></li>
			<li><a href="#" class="dropdown-toggle" data-toggle="dropdown">Наши проекты <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="portfolio-2-col.html">КРЕДДИ</a></li>
					<li><a href="portfolio.html">М2С</a></li>
					<li><a href="portfolio-4-col.html">WLP Online</a></li>
					<li><a href="portfolio-5-col.html">Arbiter</a></li>
					<li><a href="portfolio-single.html">Monemobo</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Программа стажировки <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="blog.html">О программе</a></li>
					<li><a href="single.html">Как записаться?</a></li>
				</ul>
			</li>
			<li><a href="contact.html">Контакты</a></li>
		</ul>

		<form class="menu-search" action="search-results.html"><input type="search" class="search-input"
		                                                              placeholder="Search">
			<button class="search"><span class="fa fa-search"></span></button>
		</form>

	</nav>

	<div class="container-fluid nav-head" data-spy="affix" data-offset="1">
		<div class="row">
			<div class="col-md-4 col-xs-2">
				<div id="menu-trigger" class="fa fa-bars fa-2x"></div>
			</div>
			<div class="col-md-4 col-xs-8 text-center">
				<img src="/assets/sm_logo_FTF.png" class="logo" />
			</div>
		</div>
	</div>

	<div class="jumbotron full-bg animated-bg" data-bg="/assets/fintech_style_present_cover.jpg">
		<div class="col-md-6 col-md-offset-3 centered text-center">
			<h1>FINTECH_FAB</h1>

			<p>making future not maybes</p>

			<p>
				<a class="btn btn-primary btn-lg" href="#services" role="button"><span class="fa-caret-down fa"></span>
					Подробнее</a>
			</p>
		</div>
	</div>

	<?= $content ?>

	<footer>
		<div class="container-fluid center-text-mobile">
			<div class="row">
				<div class="col-xs-12">
					<h4>О <span class="logo">FINTECH_FAB</span></h4>

					<p>Высокотехнологичная, динамичная и молодая компания, которая объединяет топ-менеджеров крупных IT- компаний и экспертов в маркетинге, финансовой сфере.</p>
				</div>

			</div>
		</div>
	</footer>
	<div class="container-fluid copyright">
		<div class="row">
			<div class="col-md-3">
				<span class="info">&copy; 2014 FINTECH_FAB <a href="#"></a></span>
			</div>
			<div class="col-md-7">
				<span class="info"><span class="fa fa-location-arrow"></span> Россия, 115114, Москва, Дербеневская наб., 11</span>
				<span class="info"><span class="fa fa-phone"></span> +7(495) 668 3020</span>
			</div>
			<div class="col-md-2">
				<span class="info"><a class="habralogo" href="http://habrahabr.ru/company/fintech_fab/"></a></span>
			</div>
		</div>
	</div>

	<!-- Functions -->
	<script src="/assets/functions.js"></script>

	<script>
		(function (i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function () {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'http://www.google-analytics.com/analytics.js', 'ga');
		ga('create', 'UA-47423084-3', 'themanoid.com');
		ga('send', 'pageview');
	</script>

	</body>
	</html>