<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

?>
	<h4>Оформление подписки</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));
?>

<?= $form->radioButtonListRow($model, 'product', Yii::app()->adminKreddyApi->getProductsList(), array("class" => "all")); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Далее →',
		)); ?>
	</div>

<?php

$this->endWidget();