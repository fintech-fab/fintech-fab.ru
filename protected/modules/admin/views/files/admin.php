<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */
/**
 * @var $itemsProvider CArrayDataProvider
 */

?>

	<h1>Управление изображениями</h1>

<?php        $this->widget('bootstrap.widgets.TbGridView', array(
	'id'           => 'images-grid',
	'dataProvider' => $itemsProvider,
	'type'         => 'striped bordered condensed',
	'columns'      => array(
		//'image',
		array(
			'name'   => 'thumb',
			'header' => 'Изображение',
			'type'   => 'html',
			'value'  => '(!empty($data["thumb"]))?Chtml::link(CHtml::image($data["thumb"],"image_thumbnail"),$data["image"]):"no image"',

		),
		array(
			'name'   => 'count_pages',
			'header' => 'Использовано на страницах',
			'value'  => '$data["count_pages"]',
		),
		array(
			'name'   => 'count_tabs',
			'header' => 'Использовано на вкладках',
			'value'  => '$data["count_pages"]',
		),
		array(
			'name'   => 'count_footer_links',
			'header' => 'Использовано в ссылках',
			'value'  => '$data["count_footer_links"]',
		),
		array(
			'class'           => 'bootstrap.widgets.TbButtonColumn',
			'template'        => '{delete}',
			'deleteButtonUrl' => '$this->grid->owner->createUrl("files/delete", array("image"=>str_replace("/uploads/images/","",$data["image"])))',
			/*'buttons' => array
			(
				'delete' => array
				(
					'url' => '$this->grid->owner->createUrl("admin/files/delete", array("image"=>"$data->thumb"))',

				),
			),*/
		),
	),
)); ?>

	Отработало за <?= sprintf('%0.5f', Yii::getLogger()
	->getExecutionTime()) ?> с. Скушано памяти: <?= round(memory_get_peak_usage() / (1024 * 1024), 2) . "MB" ?>