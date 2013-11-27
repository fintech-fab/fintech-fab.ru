<?php
/* @var $this FaqGroupController */
/* @var $model FaqGroup */

$this->pageTitle = Yii::app()->name . " - Управление категориями";

?>

<h1>Редактировать категорию <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
