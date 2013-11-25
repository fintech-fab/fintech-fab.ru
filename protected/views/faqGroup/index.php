<?php
$this->breadcrumbs = array(
	'Faq Groups',
);

$this->menu = array(
	array('label' => 'Create FaqGroup', 'url' => array('create')),
	array('label' => 'Manage FaqGroup', 'url' => array('admin')),
);
?>

<h1>Faq Groups</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
	'dataProvider' => $dataProvider,
	'itemView'     => '_view',
)); ?>
