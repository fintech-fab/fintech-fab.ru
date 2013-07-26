<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->breadcrumbs=array(
	'Footer Links'=>array('index'),
	$model->link_id,
);

$this->menu=array(
	array('label'=>'Список страниц', 'url'=>array('pages/index')),
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
	array('label'=>'Управление вкладками', 'url'=>array('tabs/admin')),
	array('label'=>'Список нижних ссылок', 'url'=>array('footerLinks/index')),
	array('label'=>'Создать нижнюю ссылку', 'url'=>array('footerLinks/create')),
	array('label'=>'Изменить нижнюю ссылку', 'url'=>array('footerLinks/update', 'id'=>$model->link_id)),
	array('label'=>'Удалить нижнюю ссылку', 'url'=>'#', 'linkOptions'=>array('submit'=>array('footerLinks/delete','id'=>$model->link_id),'confirm'=>'Вы уверены что хотите удалить эту ссылку?')),
	array('label'=>'Управление нижними ссылками', 'url'=>array('footerLinks/admin')),




	array('label'=>'Update FooterLinks', 'url'=>array('update', 'id'=>$model->link_id)),
	array('label'=>'Delete FooterLinks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->link_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View FooterLinks #<?php echo $model->link_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'link_id',
		'link_name',
		'link_title',
		'link_url',
		//'link_content',
	),
)); ?>
