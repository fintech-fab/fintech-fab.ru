<?php
/* @var $this SiteController */
/* @var $error array */
/* @var $code */

$this->pageTitle = Yii::app()->name . ' - Ошибка';
$this->breadcrumbs = array(
	'Ошибка',
);

$this->showTopPageWidget = true;
?>
<h2>Ошибка <?= $code; ?></h2>
<div class="error">
	<?= CHtml::encode($message); ?>
</div>
