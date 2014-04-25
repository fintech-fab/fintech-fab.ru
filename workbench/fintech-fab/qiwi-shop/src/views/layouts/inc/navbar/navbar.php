<?php

function echoActiveClassIfRequestMatches($requestUri)
{
	$current_file_name = basename(Request::server('REQUEST_URI'), ".php");
	if ($current_file_name == $requestUri) {
		return 'class="active"';
	}

	return '';
}

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
						<li <?= echoActiveClassIfRequestMatches("order") ?>>
							<a href="/fintech-fab/qiwi-shop/order">Создать заказ</a>
						</li>
						<li <?= echoActiveClassIfRequestMatches("table") ?>>
							<a href="/fintech-fab/qiwi-shop/table">Таблица заказов</a>
						</li>

					</ul>
					<ul class="nav navbar-nav navbar-right">

					</ul>

				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
	</header>
</div>