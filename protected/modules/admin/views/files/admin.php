<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */
/**
 * @var $itemsProvider CArrayDataProvider
 */

$this->menu = array(
	array('label' => 'Список страниц', 'url' => array('pages/index')),
	array('label' => 'Создать страницу', 'url' => array('pages/create')),
	array('label' => 'Список вкладок', 'url' => array('tabs/index')),
	array('label' => 'Создать вкладку', 'url' => array('tabs/create')),
	array('label' => 'Управление вкладками', 'url' => array('tabs/admin')),
	array('label' => 'Список нижних ссылок', 'url' => array('footerLinks/index')),
	array('label' => 'Создать нижнюю ссылку', 'url' => array('footerLinks/create')),
);

?>

<h1>Управление изображениями</h1>

<?php 		$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'images-grid',
	'dataProvider'=>$itemsProvider,
	'columns'=>array(
		array(
			'name'=>'thumb',
			'header'=>'Изображение',
			'type'=>'html',
			'value'=> '(!empty($data["thumb"]))?Chtml::link(CHtml::image($data["thumb"],"image_thumbnail"),$data["image"]):"no image"',

		),
		array(
			'name'=>'count_pages',
			'header'=>'Использовано на страницах',
			'value'=> '$data["count_pages"]',
		),
		array(
			'name'=>'count_tabs',
			'header'=>'Использовано на вкладках',
			'value'=> '$data["count_pages"]',
		),
		array(
			'name'=>'count_footer_links',
			'header'=>'Использовано в ссылках',
			'value'=> '$data["count_footer_links"]',
		),
	),
)); ?>

Отработало за <?=sprintf('%0.5f',Yii::getLogger()->getExecutionTime())?> с. Скушано памяти: <?=round(memory_get_peak_usage()/(1024*1024),2)."MB"?>