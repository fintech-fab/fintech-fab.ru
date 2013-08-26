<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->pageTitle = Yii::app()->name . " - Отображение ссылки";

?>

<h1>Отображение нижней ссылки #<?php echo $model->link_id; ?> "<?php echo $model->link_name; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'link_id',
		'link_name',
		'link_title',
		'link_url',
		//'link_content',
	),
)); ?>
