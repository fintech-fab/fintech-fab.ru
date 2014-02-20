<?php
if (empty($content)) {
	$content = '';
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>FINTECH_FAB</title>

	<?= javascript_include_tag() ?>

	<link type="text/css" href="//code.jquery.com/ui/1.10.4/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>

	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">

	<?= stylesheet_link_tag() ?>
	<?= stylesheet_link_tag("mystyle") ?>

</head>
<body>
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
					<a class="navbar-brand" href="">Наш сайт</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/vanguard">Стажировка</a></li>
						<li><a href="">Ещё страница</a></li>
						<li><a href="">И ещё одна</a></li>
					</ul>
					<form class="navbar-form navbar-right" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
	</header>
</div>
<div class="container"><?= $content ?></div>
</body>
</html>
