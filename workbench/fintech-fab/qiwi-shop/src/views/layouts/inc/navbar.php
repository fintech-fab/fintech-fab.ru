<?php
use FintechFab\QiwiShop\Widgets\NavbarAction;

?>
<div class="container">
	<header class="row">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
						<span class="icon-bar"></span> <span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">fintech fab</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class=" <?= NavbarAction::isActive(URL::route('createOrder')) ?> ">
							<a href="<?= URL::route('createOrder') ?>">Создать заказ</a>
						</li>
						<li class=" <?= NavbarAction::isActive(URL::route('ordersTable')) ?> ">
							<a href="<?= URL::route('ordersTable') ?>">Таблица заказов</a>
						</li>

					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="<?= URL::route('accountIndex') ?>" target="_blank">Аккаунт QIWI</a>
						</li>
						<li>
							<a href="<?= URL::route('profile') ?>">Профиль</a>
						</li>
					</ul>

				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
	</header>
</div>