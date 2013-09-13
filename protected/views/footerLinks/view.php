<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->link_title);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<base href="<?= Yii::app()->request->getBaseUrl(true); ?>">
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->getBaseUrl(true); ?>/static/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->getBaseUrl(true); ?>/static/css/bootstrap-overload.css" />
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->getBaseUrl(true); ?>/static/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->getBaseUrl(true); ?>/static/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?= Yii::app()->request->getBaseUrl(true); ?>/static/css/payment.css" />
</head>
<body>
<?= $model->link_content; ?>
</body>
</html>
