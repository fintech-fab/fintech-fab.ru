<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Список страниц', 'url'=>array('pages/index')),
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Управление страницами', 'url'=>array('pages/admin')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tabs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление вкладками</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) перед поисковым значением для определения правил поиска.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tabs-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'tab_id',
		'tab_order',
		'tab_name',
		'tab_title',
		//'tab_content',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
