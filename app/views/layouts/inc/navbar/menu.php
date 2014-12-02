<nav class="navbar" role="navigation">

	<div class="navbar-header">
		<strong></strong>
	</div>

	<ul class="nav">
		<li><a href="<?= URL::route('index') ?>">Главная</a></li>
		<li><a href="<?= URL::route('about') ?>">Кто мы?</a></li>
		<li><a href="<?= URL::route('projects') ?>">Наши проекты</a></li>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Программа стажировки <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li><a href="<?= URL::route('vanguard') ?>">О программе</a></li>
				<li><a href="<?= URL::route('vanguard') ?>#tab4">Как записаться?</a></li>
			</ul>
		</li>
		<li><a href="<?= URL::route('contact') ?>">Контакты</a></li>
	</ul>
</nav>
<div class="container-fluid nav-head" data-spy="affix" data-offset="1">
	<div class="row">
		<div class="col-md-4 col-xs-2">
			<div id="menu-trigger" class="fa fa-bars fa-2x"></div>
		</div>
		<div class="col-md-4 col-xs-8 text-center">
			<a href="<?= URL::route('index') ?>"><img src="/assets/sm_logo_FTF.png" class="logo" /></a>
		</div>
	</div>
</div>