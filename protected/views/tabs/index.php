<?php
/* @var $this TabsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tabs',
);

$this->menu=array(
	array('label'=>'Create Tabs', 'url'=>array('create')),
	array('label'=>'Manage Tabs', 'url'=>array('admin')),
);
?>

<h1>Tabs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
