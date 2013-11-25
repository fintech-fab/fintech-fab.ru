<?php
$this->breadcrumbs = array(
	'Faq Groups'  => array('index'),
	$model->title => array('view', 'id' => $model->id),
	'Update',
);

$this->menu = array(
	array('label' => 'List FaqGroup', 'url' => array('index')),
	array('label' => 'Create FaqGroup', 'url' => array('create')),
	array('label' => 'View FaqGroup', 'url' => array('view', 'id' => $model->id)),
	array('label' => 'Manage FaqGroup', 'url' => array('admin')),
);
?>

	<h1>Update FaqGroup <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
