<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs = array(
	'Pages' => array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pages-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Управление страницами</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
	'id'           => 'pages-grid',
	'type'         => 'striped bordered condensed',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		array('name' => 'page_id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'page_name',
		'page_title',
		//'page_content',
		array(
			'class'   => 'bootstrap.widgets.TbButtonColumn',
			'buttons' => array
			(
				'view' => array
				(
					'url' => 'Yii::app()->createUrl("admin/pages/view", array("name"=>"$data->page_name"))',
				),
			),
		),
	),
)); ?>
