<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->pageTitle = Yii::app()->name . " - Создание страницы";

?>

<h1>Создание страницы</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
