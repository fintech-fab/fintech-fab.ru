<?php
/* @var $this FaqGroupController */
/* @var $model FaqGroup */

$this->pageTitle = Yii::app()->name . " - Отображение категории";
?>

<h1>Отображение категории #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'id',
		'title',
	),
)); ?>
