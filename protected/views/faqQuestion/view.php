<?php
$this->breadcrumbs = array(
	'Faq Questions' => array('index'),
	$model->title,
);

$this->menu = array(
	array('label' => 'List FaqQuestion', 'url' => array('index')),
	array('label' => 'Create FaqQuestion', 'url' => array('create')),
	array('label' => 'Update FaqQuestion', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete FaqQuestion', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage FaqQuestion', 'url' => array('admin')),
);
?>

<h1>View FaqQuestion #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'id',
		'title',
		'answer',
		'group_id',
		'question_order',
	),
)); ?>
