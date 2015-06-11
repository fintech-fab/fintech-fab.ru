<?php
use FintechFab\Components\Form\Vanguard\FormHelper;

$information = FormHelper::getInformation();
//Данные передаются из инпутов, контроллер VanguardController -> getOrderFormData()
?>
<html>
<head>
	<title>Заявка в программу стажировки</title>
</head>
<body>
<p>
	Прилетела новая заявка в программу стажировки.<br>
	<?php
	foreach ($information['improver'] as $data) {
		$text = array_search($data, $information['improver']);
		echo $data . ' ';
		echo HTML::entities($$text) . '. <br>';
	}
	?>
</p>
</body>
</html>
