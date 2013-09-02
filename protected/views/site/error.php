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
<h2>Ошибка <?php echo $code; ?></h2>
<div class="error">
	<?php echo CHtml::encode($message); ?>
</div>
