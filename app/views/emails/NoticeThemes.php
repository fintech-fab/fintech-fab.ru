<?php
/**
 * @var string $themeName
 * @var string $baseMessage
 * @var string $comment
 */
?>
<html>
<head>
	<title><?= HTML::entities($themeName) ?></title>
</head>
<body>
<p>
	 <?= HTML::entities($baseMessage) ?>.<br>

	<br>  <?= HTML::entities($comment) ?>
</p>
</body>
</html>