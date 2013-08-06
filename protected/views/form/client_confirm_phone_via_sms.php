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
			<strong>+7<?= Yii::app()->session['ClientPersonalDataForm']['phone']; ?></strong>
			<br/><br/>
			<?php
			    // если попыток ввода ещё не было, выводим форму для отправки кода на SMS
				if(!isset($tries)||$tries==0)	{
			?>
			<span id="send_sms">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'id'=>'sendSms',
				'buttonType' => 'ajaxSubmit',
				'url'=> Yii::app()->createUrl('form/ajaxsendsms'),
				'size'=>'small',
				'label' => 'Отправить на +7'.Yii::app()->session['ClientPersonalDataForm']['phone'].' SMS с кодом подтверждения',
				'ajaxOptions'=>array('success'=>"function()
                                {
                                $('#send_sms').hide();
                                $('#sms_code_row').show();
                                $('#btnNext').show();
                                } ",
				),
			)); ?>
			</span>
			<?php
				}else if($tries >= 10){
					?>
					Число попыток ввода кода превышено! Попробуйте позже.
			<?php
				}else{
			?>
			Неверно введён код! Осталось попыток: <?php echo 10-$tries; ?>
			<?php } ?>
		</span>
		<span class="span10<?php if(!isset($tries)||$tries==0)echo ' hide';?>" id="sms_code_row">
			Введите код из SMS:
			<?php echo $form->textField( $oClientCreateForm, 'sms_code', array( 'class' => 'span4' ) ); ?>
			<?php echo $form->error($oClientCreateForm, 'sms_code'); ?>
		</span>
	</div>

		<div class="clearfix"></div>

		<div class="form-actions">
			<?php $aHide=array();
			if(!isset($tries)||$tries==0) $aHide=array("class"=>'hide');	?>
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => 'Далее →',
				'htmlOptions'=>array('id'=>'btnNext',)+$aHide,
			)); ?>
		</div>
	</div>
<?

$this->endWidget();
?>
