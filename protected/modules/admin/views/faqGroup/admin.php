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

<?php
$csrf_token_name = Yii::app()->request->csrfTokenName;
$csrf_token = Yii::app()->request->csrfToken;

$str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $('#faq-groups-grid table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                serial = $('#faq-groups-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'}) + '&{$csrf_token_name}={$csrf_token}';
                $.ajax({
                    'url': '" . $this->createUrl('sort') . "',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){
                    },
                    'error': function(request, status, error){
                        alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
                    }
                });
            },
            helper: fixHelper
        }).disableSelection();
    ";

Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>

<h1>Управление категориями вопросов</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                    => 'faq-groups-grid',
	'type'                  => 'striped bordered condensed',
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'rowCssClassExpression' => '"items[]_{$data->id}"',
	'columns'               => array(
		array('name' => 'id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'title',
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
