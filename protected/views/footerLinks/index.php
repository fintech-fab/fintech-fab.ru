<?php
/* @var $this FooterLinksController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Footer Links',
);

$this->menu=array(
	array('label'=>'Create FooterLinks', 'url'=>array('create')),
	array('label'=>'Manage FooterLinks', 'url'=>array('admin')),
);
?>

<h1>Footer Links</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
