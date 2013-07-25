<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	$model->tab_id=>array('view','id'=>$model->tab_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tabs', 'url'=>array('index')),
	array('label'=>'Create Tabs', 'url'=>array('create')),
	array('label'=>'View Tabs', 'url'=>array('view', 'id'=>$model->tab_id)),
	array('label'=>'Manage Tabs', 'url'=>array('admin')),
);
?>

<h1>Update Tabs <?php echo $model->tab_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>