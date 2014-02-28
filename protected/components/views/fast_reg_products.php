<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var                          $aProducts
 * @var IkTbActiveForm           $form
 */


?>

<ul id="productsTabs" class="nav nav-tabs">
	<?php foreach ($aProducts as $iKey => $aProduct) { ?>
		<li <?= $iKey == array_keys($aProducts)[0] ? 'class="active"' : '' ?>>
			<a href="#product<?= $aProduct['id'] ?>" data-toggle="tab" style="font-size: 20pt;"><?= $aProduct['amount'] ?></a>
		</li>
	<?php } ?>

</ul>

<div class="span6">
	<div class="row">
		<div class="tab-content" id="myTabContent">
			<?php foreach ($aProducts as $iKey => $aProduct) { ?>
				<div id="product<?= $aProduct['id'] ?>" class="tab-pane fade <?= $iKey == array_keys($aProducts)[0] ? 'active in' : '' ?>">
					<?php

					//'name' => 'Кредди 3000'
					//'subscription_lifetime' => '2592000'
					//'loan_lifetime' => '604800'

					?>
					<strong><span class="packet_name"><?= $aProduct['name'] ?></span>"</strong>
					<ul>
						<li>Размер одного займа - <span class="cost final_price"><?= $aProduct['loan_amount'] ?></span>&nbsp;руб.
						</li>

						<li>Доступная сумма - <span class="cost packet_size"><?= $aProduct['amount'] ?></span>&nbsp;руб.
						</li>
						<li>Количество займов в пакете -
							<span class="cost count_subscribe"><?= $aProduct['loan_count'] ?></span>
						</li>

						<li>Стоимость подключения -
							<span class="cost price_count"><?= $aProduct['subscription_cost'] ?></span>&nbsp;руб.
						</li>

						<li>Возврат каждого займа - в течение
							<span class="cost price_count"><?= $aProduct['loan_lifetime'] / (3600 * 24) ?></span>&nbsp;дней
							(с момента перечисления займа)
						</li>

					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="row">
			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm),
				'enableAjaxValidation' => true,
				'type'                 => 'inline',
				'clientOptions'        => array(
					'validateOnChange' => true,
					'validateOnSubmit' => false,
				),
				'action'               => Yii::app()->createUrl('/form/'),
			));

			$oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->product)) {
				$oClientCreateForm->product = reset(array_keys(Yii::app()->productsChannels->getProducts()));
			}
			?>
			<?= $form->radioButtonListRow($oClientCreateForm, 'product', Yii::app()->productsChannels->getProducts(), array("class" => "all")); ?>

			<?php
			$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->channel_id)) {
				$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannels()));
			}
			?>
			<?= $form->radioButtonList($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannels(), array("class" => "all")); ?>
			<div class="clearfix"></div>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
			<?php
			$this->endWidget();
			?>
		</div>
	</div>
</div>
<div class="span6">
	<div class="row">
		<?php
		$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
			'id'                   => get_class($oClientCreateForm) . '_fast',
			'enableAjaxValidation' => true,
			'type'                 => 'inline',
			'clientOptions'        => array(
				'validateOnChange' => true,
				'validateOnSubmit' => false,
			),
			'action'               => Yii::app()->createUrl('/form/'),
		));

		?>
		<?= $form->errorSummary($oClientCreateForm); ?>
		<p><?= $form->textFieldRow($oClientCreateForm, 'last_name'); ?></p>

		<p><?= $form->textFieldRow($oClientCreateForm, 'first_name'); ?></p>

		<p><?= $form->textFieldRow($oClientCreateForm, 'third_name'); ?></p>

		<p><?= $form->phoneMaskedRow($oClientCreateForm, 'phone', array('size' => '15')); ?></p>

		<p><?= $form->textFieldRow($oClientCreateForm, 'email'); ?></p>
		<?php $oClientCreateForm->fast_reg = 1; ?>
		<p><?= $form->hiddenField($oClientCreateForm, 'fast_reg'); ?></p>

		<p class="confirm">
			<?php
			$oClientCreateForm->agree = false;
			echo $form->checkBoxRow($oClientCreateForm, 'agree');
			?>
		</p>

		<div class="clearfix"></div>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
		)); ?>
		<?php
		$this->endWidget();
		?>
	</div>
</div>



