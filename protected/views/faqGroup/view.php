<?php
$this->breadcrumbs = array(
	'Faq Groups' => array('index'),
	$model->title,
);

$this->menu = array(
	array('label' => 'List FaqGroup', 'url' => array('index')),
	array('label' => 'Create FaqGroup', 'url' => array('create')),
	array('label' => 'Update FaqGroup', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete FaqGroup', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage FaqGroup', 'url' => array('admin')),
);
?>

<h1>View FaqGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'id',
		'title',
		'sort_order',
	),
)); ?>
