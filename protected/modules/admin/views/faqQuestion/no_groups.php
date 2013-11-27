<?php
/* @var $this FaqQuestionController */
/* @var $model FaqQuestion */

$this->pageTitle = Yii::app()->name . " - Создать вопрос";
?>

<h1>Создание вопроса</h1>

Не найдено ни одной категории вопросов. Сначала <?php echo CHtml::link('создайте', Yii::app()
	->createUrl('admin/faqGroup/create')) ?> категорию.
