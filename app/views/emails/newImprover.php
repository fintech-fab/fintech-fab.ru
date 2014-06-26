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
    Направление: <?= HTML::entities($direction) ?>.<br>    Работы: <?= HTML::entities($works) ?>.<br>
    Время: <?= HTML::entities($time) ?>.<br>      Могу ли приезжать в офис: <?= HTML::entities($visit) ?>.<br>
	Email: <?= HTML::entities($email) ?>.<br> О себе: <?= HTML::entities($about) ?>
</p>
</body>
</html>