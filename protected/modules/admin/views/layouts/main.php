<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="КРЕДДИ - ты всегда при деньгах. Как просто получить и вернуть заём? Простая и удобная услуга займов. Сколько взял – столько вернул!" />
	<meta name="keywords" content="" />
	<meta name="author" content="деньги, наличные, электронные деньги, срочно нужны, где взять, взаймы, займу, быстрые, в займы, займ, заём, займы, микрофинансовая организация, кредит, долг, вдолг, потребительские, денежный, частный, беспроцентный, ссуда, за час, кредитование, без справок, доход, срочный, экспресс, проценты, до зарплаты, неотложные, по паспорту, под расписку, выгодный, кредитные карты, кредитные системы, кредитные организации, кредитные истории, занять, краткосрочные, физическим лицам" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/bootstrap-overload.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/form.css" />

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/main.js"></script>

	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
	<?php Yii::app()->bootstrap->registerCoreCss();
	Yii::app()->bootstrap->registerYiiCss();
	Yii::app()->bootstrap->registerCoreScripts();
	?>
</head>
<body class="home">

<nav class="navbar navbar-top">
	<div class="navbar-inner navbar-inner-admin">
		<div class="container">

			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a>
			<a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/logo-slogan.png" alt="Kreddy" /> </a>


		</div>
	</div>
</nav>

<?php echo $content; ?>

</body>
</html>
