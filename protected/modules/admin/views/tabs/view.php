<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->pageTitle = Yii::app()->name . " - Отображение вкладки";


?>

<h1>Отображение вкладки #<?php echo $model->tab_id; ?> "<?php echo $model->tab_name; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'tab_id',
		'tab_name',
		'tab_title',
		//'tab_content',
		'tab_order',
	),
)); ?>
