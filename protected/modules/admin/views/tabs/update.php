<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->pageTitle = Yii::app()->name . " - Редактирование вкладки";

?>

	<h1>Редактирование вкладки #<?php echo $model->tab_id; ?> "<?php echo $model->tab_name; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>