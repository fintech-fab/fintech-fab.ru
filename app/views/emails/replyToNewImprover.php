<?php
/**
 * @var string $name
 * @var string $email
 * @var string $about
 */
?>
<html>
<head>
	<title>Принята заявка в программу стажировки</title>
</head>
<body>
<p>
	<img src="<?= $message->embed('http://fintech-fab.ru/assets/main/logo.png') ?>"><br>
	<h1>Принята заявка в программу стажировки</h1>

	<?= HTML::entities($name) ?>.<br>
	Мы рады, что вы подали заявку в программу стажировки.<br>
	Мы обязательно вам ответим.


</p>
</body>
</html>