<?php
/* @var $this FaqGroupController */
/* @var $model FaqGroup */

$this->pageTitle = Yii::app()->name . " - Управление категориями вопросов";

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('faq-group-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Управление категориями вопросов</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                    => 'faq-groups-grid',
	'type'                  => 'striped bordered condensed',
	'sortableRows'          => true,
	'sortableAttribute'     => 'sort_order',
	'sortableAjaxSave'      => true,
	'sortableAction'        => $this->createUrl('sortable'), // Custom action we added in our controller to handle updates
	'afterSortableUpdate'   => 'js:function(){}',
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'rowCssClassExpression' => '"items[]_{$data->id}"',
	'columns'               => array(
		array('name' => 'id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'title',
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'faqGroup/toggle',
			'name'         => 'show_site1',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class'        => 'bootstrap.widgets.TbToggleColumn',
			'toggleAction' => 'faqGroup/toggle',
			'name'         => 'show_site2',
			'htmlOptions'  => array('style' => 'width: 50px;'),
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
