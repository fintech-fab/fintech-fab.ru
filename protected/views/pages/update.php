<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->page_id=>array('view','id'=>$model->page_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Pages', 'url'=>array('index')),
	array('label'=>'Create Pages', 'url'=>array('create')),
	array('label'=>'View Pages', 'url'=>array('view', 'id'=>$model->page_id)),
	array('label'=>'Manage Pages', 'url'=>array('admin')),
);
?>

<h1>Update Pages <?php echo $model->page_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>