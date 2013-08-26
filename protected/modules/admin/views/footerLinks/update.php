<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */

$this->pageTitle = Yii::app()->name . " - Редактирование ссылки";

?>

	<h1>Редактирование ссылки #<?php echo $model->link_id; ?> "<?php echo $model->link_name; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>