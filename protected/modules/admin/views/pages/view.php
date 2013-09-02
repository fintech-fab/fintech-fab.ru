<?php
/* @var $this PagesController */
/* @var $model Pages */

$sUrl = Yii::app()->createAbsoluteUrl('/pages/view/' . $model->page_name);

?>

<?php
$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->page_title);
?>

<?php echo $model->page_content; ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
	'id' => 'getLink',
)); ?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4>Ссылка на страницу</h4>
</div>

<div class="modal-body">
	<p>
		<input value="<?php echo $sUrl; ?>" id="link" class="span8">
	</p>
</div>

<div class="modal-footer">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'       => 'Закрыть',
		'url'         => '#',
		'htmlOptions' => array('data-dismiss' => 'modal'),
	)); ?>
</div>

<?php $this->endWidget(); ?>
