<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs = array(
	'Pages' => array('index'),
	$model->page_id,
);

$this->showTopPageWidget = false;
?>


<div id="mainmenu">
	<?php $this->widget('zii.widgets.CMenu', array(
		'items' => array(
			array('label' => 'Вернуться к списку страниц', 'url' => array('/admin/pages'),),
			array('label' => 'Управлять страницами', 'url' => array('/admin/pages/admin'),),
			array('label' => 'Редактировать данную страницу', 'url' => array('/admin/pages/update/' . $model->page_id,),),
		),
	)); ?>
</div>


<!--h1>View Pages #<?php //echo $model->page_id; ?></h1-->

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'page_id',
		'page_name',
		'page_title',
		'page_content',
	),
));*/
?>

<?php
$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->page_title);
?>

<?php echo $model->page_content; ?>
