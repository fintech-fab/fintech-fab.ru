<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->pageTitle = Yii::app()->name . " - Редактирование страницы";

?>

	<h1>Редактирование страницы <?php echo $model->page_name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>