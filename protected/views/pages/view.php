<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs = array(
	'Pages' => array('index'),
	$model->page_id,
);

$this->showTopPageWidget = true;


/*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'page_id',
		'page_name',
		'page_title',
		'page_content',
	),
));*/


$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->page_title);

echo $model->page_content;
