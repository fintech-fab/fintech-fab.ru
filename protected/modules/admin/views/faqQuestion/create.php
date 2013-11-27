<?php
/* @var $this FaqQuestionController */
/* @var $model FaqQuestion */

$this->pageTitle = Yii::app()->name . " - Создание вопроса";
?>

<h1>Создание вопроса</h1>


<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
