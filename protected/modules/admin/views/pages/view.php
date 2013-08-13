<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs = array(
	'Pages' => array('index'),
	$model->page_id,
);

$sUrl = Yii::app()->createAbsoluteUrl('/pages/view/' . $model->page_name);

$this->showTopPageWidget = false;
?>


<div id="mainmenu">
	<?php $this->widget('zii.widgets.CMenu', array(
		'items' => array(
			array('label' => 'Список страниц', 'url' => array('/admin/pages'),),
			array('label' => 'Управление страницами', 'url' => array('/admin/pages/admin'),),
			array(
				'label' => 'Получить ссылку на страницу', 'url' => '#', 'linkOptions' => array(
				'data-toggle' => 'modal',
				'data-target' => '#getLink',
				'onclick'     => "js: $('#link').val('$sUrl');"
			),
			),
			array('label' => 'Редактировать страницу', 'url' => array('/admin/pages/update/' . $model->page_id,),),
		),
	)); ?>
</div>

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
