<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->pageTitle = Yii::app()->name . " - Управление ссылками";

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#footer-links-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление нижними ссылками</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php

$data = $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                    => 'footer-links-grid',
	'type'                  => 'striped bordered condensed',
	'sortableRows'        => true,
	'sortableAttribute'   => 'link_order',
	'sortableAjaxSave'    => true,
	'sortableAction'      => $this->createUrl('sortable'), // Custom action we added in our controller to handle updates
	'afterSortableUpdate' => 'js:function(){}',
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'rowCssClassExpression' => '"items[]_{$data->link_id}"',
	'columns'               => array(
		array('name' => 'link_id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'link_name',
		'link_title',
		'link_url',
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'footerLinks/toggle',
			'name'         => 'show_site1',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'footerLinks/toggle',
			'name'         => 'show_site2',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
