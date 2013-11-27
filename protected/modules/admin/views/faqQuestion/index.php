<?php
/* @var $this FaqGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::app()->name . " - Список вопросов";

?>

<h1>Вопросы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView'     => '_view',
)); ?>
