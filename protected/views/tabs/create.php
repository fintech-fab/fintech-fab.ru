<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tabs', 'url'=>array('index')),
	array('label'=>'Manage Tabs', 'url'=>array('admin')),
);
?>

<h1>Create Tabs</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>