<?php
$this->breadcrumbs = array(
	'Faq Questions',
);

$this->menu = array(
	array('label' => 'Create FaqQuestion', 'url' => array('create')),
	array('label' => 'Manage FaqQuestion', 'url' => array('admin')),
);
?>

<h1>Faq Questions</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
	'dataProvider' => $dataProvider,
	'itemView'     => '_view',
)); ?>
