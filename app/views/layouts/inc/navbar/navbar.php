<?php
function echoActiveClassIfRequestMatches($requestUri)
{
	$current_file_name = basename(Request::server('REQUEST_URI'), ".php");

	if ($current_file_name == $requestUri) {
		echo 'class="active"';
	}
}

function echoAuthMode()
{
	echo '<a href="" data-toggle="modal" data-target="#loginModal">Вход</a>';
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
					<a class="navbar-brand" href="/">Наш сайт</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li <?= echoActiveClassIfRequestMatches("vanguard") ?>><a href="/vanguard">Стажировка</a></li>
						<li <?= echoActiveClassIfRequestMatches("registration") ?>>
							<a href="/registration">Регистрация</a></li>
						<li><a href="">И ещё одна</a></li>
					</ul>
					<form class="navbar-form navbar-right" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li><?= echoAuthMode() ?></li>
					</ul>
					<?= View::make('vanguard.loginModal') ?>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
	</header>
</div>