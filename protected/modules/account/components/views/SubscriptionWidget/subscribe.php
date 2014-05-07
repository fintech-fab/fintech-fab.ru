<?php
/**
 * @var SubscriptionWidget  $this
 * @var IkTbActiveForm      $form
 * @var ClientSubscribeForm $oModel
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

	<div class="alert in alert-block alert-info">Обращаем Ваше внимание, что обработка запроса осуществляется ежедневно
		с 09:00 до 22:00 по московскому времени.
	</div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/selectChannel'),
));

$aClientPreProductList = Yii::app()->adminKreddyApi->getClientProductsList(false, true);
$aClientPostProductList = Yii::app()->adminKreddyApi->getClientProductsList(true, false);

$oModel->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

// если пакета в сессии нет
if ($oModel->product === false) {
	//устанавливаем в качестве выбранного пакета первый из массива доступных
	$oModel->product = reset(array_keys($aClientPostProductList));
}

?>

	<div class="product_selection">
		<h2>Оплатить сейчас</h2>
		<h5 style="margin-bottom: 30px;">(абонентская плата - <strong>900 руб/мес</strong>)</h5>
		<?php

		foreach ($aClientPreProductList as $id => $name) {
			?>
			<div style="margin: 6px 0">
				<?php
				echo CHtml::radioButton('ClientSubscribeForm[product]', ($id == $oModel->product), array(
					'id'    => 'prod' . $id,
					'value' => $id,
				));
				echo CHtml::label($name, 'prod' . $id, array(
					'style' => 'display: inline; margin-left: 10px; font-size: 1.1em;'
				));
				?>
			</div>
		<?php
		}
		?>
	</div>
	<div class="product_selection">
		<h2>Оплатить потом</h2>
		<h5 style="margin-bottom: 30px;">(абонентская плата - <strong>1000 руб/мес</strong>)</h5>
		<?php
		foreach ($aClientPostProductList as $id => $name) {
			?>
			<div style="margin: 6px 0;">
				<?php
				echo CHtml::radioButton('ClientSubscribeForm[product]', ($id == $oModel->product), array(
					'id'    => 'prod' . $id,
					'value' => $id,
				));
				echo CHtml::label($name, 'prod' . $id, array(
					'style' => 'display: inline; margin-left: 10px; font-size: 1.1em;'
				));
				?>
			</div>
		<?php
		}
		?>
	</div>
	<div class="clearfix"></div>
	<div><?= $form->error($oModel, 'product'); ?></div>
	<div class="clearfix"></div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => $this->getSubscribeButtonLabel(),
		)); ?>
	</div>

<?php
$this->endWidget();
