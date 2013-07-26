<?php
/* @var $this PagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pages',
);

$this->menu=array(
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Управление страницами', 'url'=>array('pages/admin')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
	array('label'=>'Управление вкладками', 'url'=>array('tabs/admin')),
	array('label'=>'Список нижних ссылок', 'url'=>array('footerLinks/index')),
	array('label'=>'Создать нижнюю ссылку', 'url'=>array('footerLinks/create')),
	array('label'=>'Управление нижними ссылками', 'url'=>array('footerLinks/admin')),
);
?>

<h1>Страницы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
