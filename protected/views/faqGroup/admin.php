<?php
$this->breadcrumbs = array(
	'Faq Groups' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List FaqGroup', 'url' => array('index')),
	array('label' => 'Create FaqGroup', 'url' => array('create')),
);

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

<h1>Manage Faq Groups</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be
	done. </p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search', array(
		'model' => $model,
	)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'           => 'faq-group-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'id',
		'title',
		'sort_order',
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
