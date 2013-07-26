<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->breadcrumbs=array(
	'Footer Links'=>array('index'),
	$model->link_id=>array('view','id'=>$model->link_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FooterLinks', 'url'=>array('index')),
	array('label'=>'Create FooterLinks', 'url'=>array('create')),
	array('label'=>'View FooterLinks', 'url'=>array('view', 'id'=>$model->link_id)),
	array('label'=>'Manage FooterLinks', 'url'=>array('admin')),
);
?>

<h1>Update FooterLinks <?php echo $model->link_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>