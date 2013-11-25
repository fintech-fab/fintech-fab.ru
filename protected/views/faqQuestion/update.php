<?php
$this->breadcrumbs = array(
	'Faq Questions' => array('index'),
	$model->title   => array('view', 'id' => $model->id),
	'Update',
);

$this->menu = array(
	array('label' => 'List FaqQuestion', 'url' => array('index')),
	array('label' => 'Create FaqQuestion', 'url' => array('create')),
	array('label' => 'View FaqQuestion', 'url' => array('view', 'id' => $model->id)),
	array('label' => 'Manage FaqQuestion', 'url' => array('admin')),
);
?>

	<h1>Update FaqQuestion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
