<?php
/* @var $this FaqGroupController */
/* @var $model FaqGroup */

$this->pageTitle = Yii::app()->name . " - Список категорий";
?>

<h1>Создание категории вопросов</h1>


<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
