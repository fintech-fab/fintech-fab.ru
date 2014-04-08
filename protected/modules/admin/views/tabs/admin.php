<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->pageTitle = Yii::app()->name . " - Управление вкладками";

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
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                    => 'tabs-grid',
	'type'                  => 'striped bordered condensed',
	'sortableRows'          => true,
	'sortableAttribute'     => 'tab_order',
	'sortableAjaxSave'      => true,
	'sortableAction'        => $this->createUrl('sortable'), // Custom action we added in our controller to handle updates
	'afterSortableUpdate'   => 'js:function(){}',
	//'responsiveTable' =&gt; true,              // Mobile Optimize the table
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'rowCssClassExpression' => '"items[]_{$data->tab_id}"',
	'columns'               => array(
		array('name' => 'tab_id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'tab_name',
		'tab_title',
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'tabs/toggle',
			'name'         => 'show_site1',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'tabs/toggle',
			'name'         => 'show_site2',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
