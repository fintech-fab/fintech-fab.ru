<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оформление пакета";
?>
	<h4>Оформление пакета</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$model->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
//проверяем, есть ли продукт в сессии, и есть ли список продуктов
if ($model->product === false && Yii::app()->adminKreddyApi->getClientProductsAndChannelsList() !== false) {
	//устанавливаем в качестве выбранного продукта первый в массиве
	$model->product = reset(array_keys(Yii::app()->adminKreddyApi->getClientProductsAndChannelsList()));
}
?>

<?= $form->radioButtonListRow($model, 'product', Yii::app()->adminKreddyApi->getClientProductsAndChannelsList(), array("class" => "all")); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Оформить пакет',
		)); ?>
	</div>

<?php

$this->endWidget();
