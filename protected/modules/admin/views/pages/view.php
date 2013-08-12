<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->page_id,
);

$this->showTopPageWidget = false;
?>

<!--h1>View Pages #<?php //echo $model->page_id; ?></h1-->

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'page_id',
		'page_name',
		'page_title',
		'page_content',
	),
));*/ ?>

<?php
$this->pageTitle=Yii::app()->name." - ".CHtml::encode($model->page_title);
?>

<?php echo $model->page_content; ?>
