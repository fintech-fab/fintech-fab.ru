<?php
/* @var FormController $this*/
/* @var ClientConfirmPhoneViaSMSForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */

?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$this->pageTitle=Yii::app()->name;

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id' => get_class($oClientCreateForm),
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnChange'=>true,
			'validateOnSubmit'=>true,
		),
		'action' => Yii::app()->createUrl('/form/sendcode'),
	));

	?>

	<div class="row span5">
		<span class="span10">
			Для завершения регистрации Вам нужно подтвердить свой телефон.
			<br/>
			Ваш телефон:
			<?php Yii::app()->session['ClientPersonalDataForm']['phone']; ?>
			<br/><br/>
			<span id="send_sms">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'id'=>'sendSms',
				'buttonType' => 'ajaxSubmit',
				'url'=> Yii::app()->createUrl('form/ajaxsendsms'),
				'size'=>'small',
				'label' => 'Отправить SMS с кодом подтверждения',
				'ajaxOptions'=>array('success'=>"function()
                                {
                                $('#send_sms').hide();
                                $('#sms_code_row').show();
                                } ",
				),
			)); ?>
			</span>
		</span>
		<span class="span10 hide" id="sms_code_row">
			<?php echo $form->textFieldRow( $oClientCreateForm, 'sms_code', array( 'class' => 'span4' ) ); ?>
		</span>
	</div>

		<div class="clearfix"></div>

		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => 'Далее →',
			)); ?>
		</div>
	</div>
<?

$this->endWidget();
?>
