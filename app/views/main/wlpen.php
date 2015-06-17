<nav class="container-fluid nav-head" data-spy="affix" data-offset="100">
	<div class="row">
		<div class="col-md-2 col-xs-3 text-center">
			<a href="<?= URL::route('en') ?>"><img src="assets/images/sm_logo_FTF.png" class="logo" /></a>
		</div>
		<div class="col-md-2 col-xs-3 text-center col-md-offset-8 col-xs-offset-10">
			<a href="<?= URL::route('index') ?>">РУССКИЙ</a>
		</div>
	</div>
</nav>

<div class="container-fluid breadcrumb-container">
	<div class="row">
		<!--		<div class="col-md-12">--><!--			<ol class="breadcrumb">-->
		<!--				<li><a href="index.html">Home</a></li>--><!--				<li class="active">Contact</li>-->
		<!--			</ol>--><!--		</div>-->
		<div class="col-md-12">
			<h1 class="h1-title">WLP ONLINE</h1>
		</div>
	</div>
</div>

<div class="container-fluid separator">
	<div class="row">
		<div class="col-md-12">
			<p class="text">Off-the-shelf online financing solution for
				microfinance organisations. The solution allows to start operating throughout Russia
				(85 regions, more <br> than 1 000 cities) in the shortest time.
			</p>
		</div>
		<div class="col-md-12 margin-bottom-40">
			<h2>BENEFITS</h2>

			<p>
				There is no need  to visit the office to receive a loan.
			</p>
			<p>
				White Label Platform processes loan applications, identifies clients and
				transfers loans remotely.
			</p>
			<p>
				Reloan can be transferred to a bank card in a few minutes 24/7.
			</p>
			<p>
				Platform conforms to the requirements of the Federal Laws
				of the Russian Federation - 151, 152, 115, 218, 353.
			</p>

			<div class="margin-bottom-20"></div>

			<a href="/assets/files/WLP.pdf" class="btn btn-primary"><span class="fa-download fa"></span>
				Download presentation
			</a>
		</div>
		<div class="col-md-12">
			<div id="links">
				<a href="/assets/images/projects/wlp/anketa.png" title="Анкета" data-gallery="">
					<img src="/assets/images/projects/wlp/thumbs/anketa.png" alt="Анкета"> </a>
				<a href="/assets/images/projects/wlp/card.png" title="Карточка" data-gallery="">
					<img src="/assets/images/projects/wlp/thumbs/card.png" alt="Карточка"> </a>
				<a href="/assets/images/projects/wlp/report.png" title="Отчет" data-gallery="">
					<img src="/assets/images/projects/wlp/thumbs/report.png" alt="Отчет"> </a>
				<a href="/assets/images/projects/wlp/filter.png" title="Кросс-фильтр" data-gallery="">
					<img src="/assets/images/projects/wlp/thumbs/filter.png" alt="Кросс-фильтр"> </a>
				<a href="/assets/images/projects/wlp/add-card.png" title="Привязка карты" data-gallery="">
					<img src="/assets/images/projects/wlp/thumbs/add-card.png" alt="Привязка карты"> </a>
			</div>
		</div>
	</div>
</div>

<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
	<!-- The container for the modal slides -->
	<div class="slides"></div>
	<!-- Controls for the borderless lightbox -->
	<h3 class="title"></h3>
	<a class="prev">‹</a> <a class="next">›</a> <a class="close">×</a> <a class="play-pause"></a>
	<ol class="indicator"></ol>
	<!-- The modal dialog, which will be used to wrap the lightbox content -->
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body next"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left prev">
						<i class="glyphicon glyphicon-chevron-left"></i> Назад
					</button>
					<button type="button" class="btn btn-primary next">
						Дальше <i class="glyphicon glyphicon-chevron-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<footer id="contact">
	<div class="container-fluid copyright">
		<div class="row">
			<div class="col-md-3 col-xs-8">
				<span class="info">&copy; 2015 FINTECH_FAB <a href="#"></a></span>
			</div>
			<div class="col-md-6 col-xs-8">
				<span class="info"><span class="fa fa-location-arrow text-center"></span> 11, Derbenevskaya nab., Moscow, 115114, Russia</span>
			</div>
			<div class="col-md-3 col-xs-8">
				<div class="info"><span class="fa fa-phone"></span> +7(495) 668 3020</div>
				<div class="info"><span class="fa fa-envelope-o"></span> info@fintech-fab.ru</div><br>
				<div class="info"><span class="fa fa-briefcase"></span> cv@fintech-fab.ru</div>
			</div>
		</div>
	</div>
</footer>

<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="/assets/javascripts/bootstrap-image-gallery.min.js"></script>
