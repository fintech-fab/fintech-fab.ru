<?php
/* @var $this PagesController */
/* @var $model Pages */
/* @var $bShowBrowserWidget */

$this->breadcrumbs = array(
	'Pages' => array('index'),
	$model->page_id,
);

$this->showTopPageWidget = true;

if($bShowBrowserWidget){
	$this->widget('CheckBrowserWidget');
}

$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->page_title);

echo $model->page_content;
