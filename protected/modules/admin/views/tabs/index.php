<?php
/* @var $this TabsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::app()->name . " - Список вкладок";

?>

<h1>Вкладки</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView'     => '_view',
)); ?>
