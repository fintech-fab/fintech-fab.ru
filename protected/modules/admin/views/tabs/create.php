<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->pageTitle = Yii::app()->name . " - Создание вкладки";

?>

	<h1>Создание вкладки</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>