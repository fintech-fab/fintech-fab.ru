<?php
$inputs = New \FintechFab\Models\vanguardForms();
$mass = $inputs->mass();
?>
<html>
<head>
	<title>Заявка в программу стажировки</title>
</head>
<body>
<p>
	Прилетела новая заявка в программу стажировки.<br>
	<?php
	foreach ($mass['improver'] as $data) {
		$asd = array_search($data, $mass['improver']);
		echo $data . ' ';
		echo HTML::entities($$asd) . '. <br>';
	}
	?>
</p>
</body>
</html>