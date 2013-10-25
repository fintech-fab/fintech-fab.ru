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

        $('#footer-links-grid table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                serial = $('#footer-links-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'}) + '&{$csrf_token_name}={$csrf_token}';
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

<h1>Управление нижними ссылками</h1>

<p>
	Вы также можете использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b> or <b>=</b>) перед поисковым значением для определения правил поиска. </p>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                    => 'footer-links-grid',
	'type'                  => 'striped bordered condensed',
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'rowCssClassExpression' => '"items[]_{$data->link_id}"',
	'columns'               => array(
		array('name' => 'link_id', 'header' => 'ID', 'htmlOptions' => array('style' => 'width: 50px;')),
		'link_name',
		'link_title',
		'link_url',
		array(
			'name'=>'show_site1',
			'value'=> '($data["show_site1"])?"Да":"Нет"'
		),
		array(
			'name'=>'show_site2',
			'value'=> '($data["show_site2"])?"Да":"Нет"'
		),
		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
