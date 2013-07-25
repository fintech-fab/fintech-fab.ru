<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	$model->tab_id,
);

$this->menu=array(
	array('label'=>'List Tabs', 'url'=>array('index')),
	array('label'=>'Create Tabs', 'url'=>array('create')),
	array('label'=>'Update Tabs', 'url'=>array('update', 'id'=>$model->tab_id)),
	array('label'=>'Delete Tabs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tab_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tabs', 'url'=>array('admin')),
);
?>

<h1>View Tabs #<?php echo $model->tab_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'tab_id',
		'tab_name',
		'tab_title',
		'tab_content',
		'tab_order',
	),
)); ?>
