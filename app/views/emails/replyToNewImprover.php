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

<p><img src="http://fintech-fab.ru/assets/main/logo.png" width="185" height="87"></p>

<p>Приветствуем вас, <?= HTML::entities($name) ?>!</p>

<p><strong>Вы отправили заявку на участие в программе стажировки FINTECH_FAB</strong></p>

<p>Подождите день (максимум два) - мы обязательно вам ответим.</p>

<p>(если я не буду в отпуске)</p>

<p>
	С уважением,<br> Михаил<br> m.novikov@fintech-fab.ru<br> <a href="http://fintech-fab.ru">fintech-fab.ru</a>
</p>

</body>
</html>