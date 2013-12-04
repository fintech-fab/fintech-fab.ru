<?php
/* @var DefaultController $this */
/* @var AddCardForm $model */
/* @var IkTbActiveForm $form */
/* @var $sError */

$this->pageTitle = Yii::app()->name . " - Привязка банковской карты";
?>
	<h4>Привязка банковской карты</h4>

<?php if (!empty($sError)): ?>
	<div class="alert alert-error"><?= $sError ?></div>
<?php endif; ?>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'add-card',
	'action'      => Yii::app()->createUrl('/account/addCard'),
	'htmlOptions' => array(
		'autocomplete' => 'off',
	)
));
?>
	<div class="alert alert-warning"><h4>Уважаемый Клиент:</h4>
		<ul>
			<li><?= AdminKreddyApiComponent::C_CARD_MSG_REQUIREMENTS; ?>
			</li>
			<?php
			//если карта уже привязана, то выдаем предупреждение
			if (Yii::app()->adminKreddyApi->getIsClientCardExists()): ?>
				<li>При привязке новой банковской карты, данные старой карты удаляются.</li>
			<?php endif; ?>
			<li>
				В данный момент перечисление займов доступно только на карты MasterCard. В ближайшее время перечисления
				станут доступны и на карты Visa. Благодарим за понимание!
			</li>
			<?php if (Yii::app()->adminKreddyApi->checkCardVerifyExists()): ?>
				<li>На Вашей карте будет заблокирована случайная сумма не более чем на 2 часа. Обращаем Ваше внимание -
					на карте должно быть не менее 10 рублей.
				</li>
			<?php endif; ?>
		</ul>
		<p>
			<strong>Будьте внимательны! Количество попыток ввода данных строго ограничено.</strong>
		</p>
	</div>
<?= $form->errorSummary($model) ?>

<?= $form->labelEx($model, 'iCardType') ?>
<?= $form->radioButtonList($model, 'iCardType', Dictionaries::$aCardTypes) ?>

	<div style="background: url('/static/img/bankcard.png'); width: 534px; height: 263px;">
		<?= $form->textField($model, 'sCardPan', array('maxlength' => '20', "style" => "position: relative; top: 45px; left: 113px; width: 175px;")); ?>

		<?=
		$form->typeAheadField($model, 'sCardMonth', array(
			'source' => array_values(Dictionaries::$aMonthsDigital),
		), array("style" => "position: relative; top: 100px; left: 13px; width: 24px;", 'maxlength' => '2')) ?>

		<?=
		$form->typeAheadField($model, 'sCardYear', array(
			'source' => array_values(Dictionaries::getYears()),
		), array("style" => "position: relative; top: 100px; left: 27px; width: 24px;", 'maxlength' => '2')) ?>

		<?= $form->textField($model, 'sCardCvc', array("style" => "position: relative; top: 101px; left: 125px; width: 35px;", 'size' => '3', 'mask' => '999', 'maxlength' => '3')); ?>

		<?= $form->textField($model, 'sCardHolderName', array("style" => "position: relative; top: 125px; left: 53px; width: 235px;")); ?>
	</div>


<?= $form->checkBoxRow($model, 'bConfirm'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Привязать карту',
		));
		?>
	</div>

<?php

$this->endWidget();
