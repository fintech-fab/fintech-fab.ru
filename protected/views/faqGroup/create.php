<?php
$this->breadcrumbs = array(
	'Faq Groups' => array('index'),
	'Create',
);

$this->menu = array(
	array('label' => 'List FaqGroup', 'url' => array('index')),
	array('label' => 'Manage FaqGroup', 'url' => array('admin')),
);
?>

	<h1>Create FaqGroup</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
