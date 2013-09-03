<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->page_title);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<base href="<?php echo Yii::app()->request->getBaseUrl(true); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true); ?>/static/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true); ?>/static/css/bootstrap-overload.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true); ?>/static/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true); ?>/static/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true); ?>/static/css/payment.css" />
</head>
<body>
<?php echo $model->page_content; ?>
</body>
</html>
