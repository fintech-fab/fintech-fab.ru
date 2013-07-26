<?php
/* @var $this FooterLinksController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Footer Links',
);

$this->menu=array(
	array('label'=>'Список страниц', 'url'=>array('pages/index')),
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
	array('label'=>'Управление вкладками', 'url'=>array('tabs/admin')),
	array('label'=>'Создать нижнюю ссылку', 'url'=>array('footerLinks/create')),
	array('label'=>'Управление нижними ссылками', 'url'=>array('footerLinks/admin')),

);
?>

<h1>Нижние ссылки</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
