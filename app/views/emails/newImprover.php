<?php
/**
 * @var string $name
 * @var string $email
 * @var string $about
 */
?>
<html>
<head>
	<title>Заявка в программу стажировки</title>
</head>
<body>
<p>
	Прилетела новая заявка в программу стажировки.<br> Имя: <?= HTML::entities($name) ?>.<br>
	Email: <?= HTML::entities($email) ?>.<br> О себе: <?= HTML::entities($about) ?>
</p>
</body>
</html>