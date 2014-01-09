<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";
?>
	<h4>Подключение Пакета</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$this->widget('application.modules.account.components.ShowChannelsWidget', array('aAllChannels' => Yii::app()->adminKreddyApi->getProductsChannels(), 'aAvailableChannelKeys' => Yii::app()->adminKreddyApi->getClientChannels(),));

?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Подключить Пакет',
		)); ?>
	</div>

<?php

$this->endWidget();
