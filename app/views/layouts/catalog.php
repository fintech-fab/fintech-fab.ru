<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//cdn.rawgit.com/FezVrasta/bootstrap-material-design/master/dist/css/material-wfont.min.css" rel="stylesheet">
	<link href="//cdn.rawgit.com/FezVrasta/bootstrap-material-design/master/dist/css/material.min.css" rel="stylesheet">

</head>
<body>

<div class="container">
	<?= $content ?>
</div>

<?php

if (CategorySite::queryLog()) {
	?>
	<div class="container">
	<h4>database query log</h4>
	<?php
	foreach (CategorySite::queryLog() as $query) {
		print_r('<pre>' . $query . '</pre>');
	}
	?></div><?php
}
?>

<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/FezVrasta/bootstrap-material-design/master/dist/js/material.min.js"></script>

</body>
</html>