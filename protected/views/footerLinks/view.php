<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->breadcrumbs=array(
	'Footer Links'=>array('index'),
	$model->link_id,
);

$this->menu=array(
	array('label'=>'List FooterLinks', 'url'=>array('index')),
	array('label'=>'Create FooterLinks', 'url'=>array('create')),
	array('label'=>'Update FooterLinks', 'url'=>array('update', 'id'=>$model->link_id)),
	array('label'=>'Delete FooterLinks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->link_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FooterLinks', 'url'=>array('admin')),
);
?>

<h1>View FooterLinks #<?php echo $model->link_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'link_id',
		'link_name',
		'link_title',
		'link_url',
		'link_content',
	),
)); ?>
