<?php
/**
 * @var $content
 */
Yii::app()->bootstrap->registerBootstrapCss();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Кредди</title>
	<meta charset="UTF-8">
	<link href="/static/landing/css/style.css" rel="stylesheet" />
	<link href="/static/landing/css/selectbox.css" rel="stylesheet" />
<!--	<script src="/static/landing/js/jquery-1.11.1.min.js" type="text/javascript"></script>-->
	<script src="/static/landing/js/form.js" type="text/javascript"></script>
	<script src="/static/landing/js/select.js" type="text/javascript"></script>
	<script src="/static/landing/js/jquery.selectbox.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?= Yii::app()->request->baseUrl; ?>/static/js/main.js?v=6"></script>
</head>
<body>
<div id="main">
	<?= $content ?>
</div>
<div id="footer">
	<p>Микрофинансовая организация<br /> общество с ограниченной ответственностью “Финансовые Решения”.<br />
		Свидетельство о внесении в государственный реестр МФО 2110177000213 от 19.07.2011 г.<br /> ОГРН
		1117746371270<br /> Г. Москва, Гончарная наб.1, стр.4<br /> Тел.: 8-800-555-75-78<br /><br /> *В зависимости от
		одобренного продукта</p>
</div>
</body>
</html>
