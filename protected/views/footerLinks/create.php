<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->breadcrumbs=array(
	'Footer Links'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FooterLinks', 'url'=>array('index')),
	array('label'=>'Manage FooterLinks', 'url'=>array('admin')),
);
?>

<h1>Create FooterLinks</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>