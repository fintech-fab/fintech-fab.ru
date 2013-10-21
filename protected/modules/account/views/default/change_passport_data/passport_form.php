<?php
/* @var DefaultController $this */
/* @var ChangePassportDataForm $oChangePassportForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение паспортных данных";
?>
	<h4>Изменение паспортных данных</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action' => Yii::app()->createUrl('/account/changePassport'),
));
?>

	<div class="control-group">
		<div class="row">
			<div class="span5">
				<h5>Старый паспорт</h5>
			</div>
		</div>
		<div class="row">
			<div class="span3">
				<?= $form->labelEx($oChangePassportForm, 'old_passport_number', array('class' => 'control-label')); ?>
				<div class="controls"><?= $form->maskedTextField($oChangePassportForm, 'old_passport_series', '9999', array('style' => 'width: 40px;', 'size' => '4', 'maxlength' => '4')); ?></div>
			</div>
			<div class="span2">
				<span>/</span>
				<?= $form->maskedTextField($oChangePassportForm, 'old_passport_number', '999999', array('style' => 'width: 60px;', 'size' => '6', 'maxlength' => '6')); ?>
			</div>
		</div>
		<div class="row">
			<div class="span5">
				<div style="margin-left: 180px;">
					<?= $form->error($oChangePassportForm, 'old_passport_series'); ?>
					<?= $form->error($oChangePassportForm, 'old_passport_number'); ?></div>
			</div>
		</div>
	</div>

	<div class="control-group">
		<div class="row">
			<div class="span5">
				<h5>Новый паспорт</h5>
				<?= $form->textFieldRow($oChangePassportForm, 'last_name'); ?>
				<?= $form->textFieldRow($oChangePassportForm, 'first_name'); ?>
				<?= $form->textFieldRow($oChangePassportForm, 'third_name'); ?>
			</div>
		</div>
		<div class="row">
			<div class="span3">
				<?= $form->labelEx($oChangePassportForm, 'passport_number', array('class' => 'control-label')); ?>
				<div class="controls"><?= $form->maskedTextField($oChangePassportForm, 'passport_series', '9999', array('style' => 'width: 40px;', 'size' => '4', 'maxlength' => '4')); ?></div>
			</div>
			<div class="span2">
				<span>/</span>
				<?= $form->maskedTextField($oChangePassportForm, 'passport_number', '999999', array('style' => 'width: 60px;', 'size' => '6', 'maxlength' => '6')); ?>
			</div>
		</div>
		<div class="row">
			<div class="span5">
				<div style="margin-left: 180px;">
					<?= $form->error($oChangePassportForm, 'passport_series'); ?>
					<?= $form->error($oChangePassportForm, 'passport_number'); ?></div>
			</div>
		</div>
	</div>

<?= $form->dateMaskedRow($oChangePassportForm, 'passport_date'); ?>

<?= $form->fieldMaskedRow($oChangePassportForm, 'passport_code', array('mask' => '999-999', 'size' => '7', 'maxlength' => '7')); ?>
<?= $form->textFieldRow($oChangePassportForm, 'passport_issued'); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить заявку',
		)); ?>
	</div>

<?php
$this->endWidget();
