<?php /* @var $this Controller */ ?>

<!--suppress HtmlUnknownTarget -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="Russian" />

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="КРЕДДИ - ты всегда при деньгах. Как просто получить и вернуть займ? Простая и удобная услуга займов. Сколько взял – столько вернул!" />
<meta name="keywords" content="" />
<meta name="author" content="деньги, наличные, электронные деньги, срочно нужны, где взять, взаймы, займу, быстрые, в займы, займ, заём, займы, микрофинансовая организация, кредит, долг, вдолг, потребительские, денежный, частный, беспроцентный, ссуда, за час, кредитование, без справок, доход, срочный, экспресс, проценты, до зарплаты, неотложные, по паспорту, под расписку, выгодный, кредитные карты, кредитные системы, кредитные организации, кредитные истории, занять, краткосрочные, физическим лицам" />

<title><?= CHtml::encode($this->pageTitle); ?></title>
<?php //TODO сделать в конфиге версию static-файлов и грузить её сюда  ?>

<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/main.css?v=5" />
<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/bootstrap-overload.css?v=5" />
<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/form.css?v=1" />
<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/style.css?v=7" />
<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/payment.css?v=1" />
<script type="text/javascript" src="<?= Yii::app()->request->baseUrl; ?>/static/js/main.js?v=4"></script>
<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('maskedinput');
Yii::app()->clientScript->registerCoreScript('yiiactiveform');
?>
<link rel="shortcut icon" href="<?= Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />
