<?php
$this->breadcrumbs = array(
	'Faq Questions' => array('index'),
	'Create',
);

$this->menu = array(
	array('label' => 'List FaqQuestion', 'url' => array('index')),
	array('label' => 'Manage FaqQuestion', 'url' => array('admin')),
);
?>

	<h1>Create FaqQuestion</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
