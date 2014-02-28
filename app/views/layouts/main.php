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
	<script src="//api-maps.yandex.ru/2.0/?load=package.standard,package.geoObjects&lang=ru-RU" type="text/javascript"></script>
	<?= javascript_include_tag() ?>

	<link type="text/css" href="//code.jquery.com/ui/1.10.4/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>

	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">

	<?= stylesheet_link_tag() ?>

</head>
<body>
<div class="container">
	<div><img src="/assets/main/logo.png" border="0" width="370" height="175" class="img" /></div>
	<div class="row text-center"><h2> [cайт на стадии разработки]</h2></div>

	<div class="row"><?= $content ?></div>


	<div class="row mt20">
		<div class="col-xs-2">
			<p style="font-size:18pt;">Наши<br>проекты:</p>
		</div>
		<div class="col-xs-2">
			<a href="http://kreddy.ru" target="_blank">
				<img src="/assets/main/kreddy.png" width="230" height="49" style="vertical-align:middle;" /> </a>
		</div>
		<div class="col-xs-3 pull-right text-right">
			<a class="fintech_lab_projects" href="http://fintech-lab.com/projects" target="_blank">
				<p class="projects">ПРОЕКТЫ</p>

				<p class="fintech_lab">FINTECH_LAB</p>
			</a>
		</div>
	</div>
	<div class='clear'>&nbsp;</div>

	<div class="row" style="height: 20px;">&nbsp;</div>
	<div class='clear'>&nbsp;</div>
</div>
</body>
</html>
