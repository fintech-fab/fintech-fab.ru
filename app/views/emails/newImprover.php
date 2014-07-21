<?php
use FintechFab\Components\Form\Vanguard\FormHelper;

$Information = FormHelper::getInformation();
?>
<html>
<head>
	<title>Заявка в программу стажировки</title>
</head>
<body>
<p>
	Прилетела новая заявка в программу стажировки.<br>
	<?php
	foreach ($Information['improver'] as $data) {
		$text = array_search($data, $Information['improver']);
		echo $data . ' ';
		echo HTML::entities($$text) . '. <br>';
	}
	?>
</p>
</body>
</html>