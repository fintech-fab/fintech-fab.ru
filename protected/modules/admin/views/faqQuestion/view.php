<?php
/* @var $this FaqGroupController */
/* @var $model FaqGroup */

$this->pageTitle = Yii::app()->name . " - Отображение вопроса";
?>

<h1>Отображение вопроса #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'       => $model,
	'attributes' => array(
		'id',
		array(
			'label' => $model->getAttributeLabel('group_id'),
			'value' => function ($data) {
					return $data->group->title;
				}
		),
		'title',
	),
));

echo $model->answer;?>
