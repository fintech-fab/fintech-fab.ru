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
	<div class="alert alert-warning"><h4>Уважаемый Клиент, обращаем Ваше внимание:</h4>
		<ul>
			<?php
			//если карта уже привязана, то выдаем предупреждение
			if (Yii::app()->adminKreddyApi->getIsClientCardExists()): ?>
				<li>При привязке новой банковской карты, данные старой карты удаляются.</li>
			<?php endif; ?>
			<li>
				Сейчас перечисление займов доступно только на карты Master Card. В ближайшем времени перечисления станут
				доступны и на карты Visa. Благодарим за понимание!
			</li>
			<li>На Вашей карте будет заблокирована случайная сумма не более чем на 2 часа. Обращаем Ваше внимание - на
				карте должно быть не менее 10 рублей.
			</li>
		</ul>
		<?= $form->checkBoxRow($model, 'bConfirm'); ?>
	</div>

<?= $form->radioButtonListRow($model, 'iCardType', Dictionaries::$aCardTypes) ?>
<?= $form->textFieldRow($model, 'sCardPan', array('size' => '20', 'maxlength' => '20')); ?>


<?= $form->labelEx($model, 'sCardMonth', array('class' => 'control-label')); ?>

	<div class="controls">
		<?= $form->dropDownList($model, 'sCardMonth', Dictionaries::$aMonthsDigital, array('style' => 'width: 60px;')); ?>
		<span style="font-size: 14pt;">/</span>
		<?= $form->dropDownList($model, 'sCardYear', Dictionaries::getYears(), array('style' => 'width: 80px;')); ?>
	</div>

<?= $form->error($model, 'sCardMonth'); ?>
<?= $form->error($model, 'sCardYear'); ?>

<?= $form->textFieldRow($model, 'sCardCvc', array('style' => 'width: 60px;', 'size' => '3', 'maxlength' => '3')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Привязать карту',
		)); ?>
	</div>

<?php

$this->endWidget();
