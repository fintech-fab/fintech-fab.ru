<?php
/* @var FormController $this*/
/* @var ClientConfirmPhoneViaSMSForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm
 * @var integer $phone
 * @var bool $flagSmsSent
 */

/*
 * Ввести код подтверждения из SMS
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
		'action' => Yii::app()->createUrl('/form/checksmscode'),
	));

	// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода
	$flagHideForm = (empty($flagSmsSent) || !empty($flagExceededTries));
	?>

	<div class="row span5">
		<span class="span10">
			Для завершения регистрации Вам нужно подтвердить свой телефон.
			<br/>
			Ваш телефон:
			<strong>+7<?php echo $phone; ?></strong>
			<br/><br/>
			<?php
			    // если SMS на телефон ещё не отсылалось
				if(empty($flagSmsSent))	{
			?>
			<span id="send_sms">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'id'=>'sendSms',
				'buttonType' => 'ajaxSubmit',
				'url'=> Yii::app()->createUrl('form/ajaxsendsms'),
				'size'=>'small',
				'label' => 'Отправить на +7'.Yii::app()->session['ClientPersonalDataForm']['phone'].' SMS с кодом подтверждения',
				'ajaxOptions'=>array(
					'success'=>"function(data)
                                {
                                	$('#send_sms').hide();
                                	$('#sms_code_row').show();
                                	$('.form-actions').show();
                               		$('#actionAnswer').html(data).show();
                               		if(data==''){
                               			$('#actionAnswer').html('".Dictionaries::C_SMS_SUCCESS."').show();
                               		}
                                } ",
				),
			)); ?>
			</span>
			<?php } ?>
		</span>

		<span class="span10<?php if($flagHideForm)echo ' hide';?>" id="sms_code_row">
			<label>Введите код из SMS:</label>
			<?php echo $form->textField( $oClientCreateForm, 'sms_code', array( 'class' => 'span4' ) ); ?>
			<?php echo $form->error($oClientCreateForm, 'sms_code'); ?>
		</span>
		<span class="span10 help-block error<?php if(empty($actionAnswer)){ echo " hide"; }?>" id="actionAnswer">
			<?php if(!empty($actionAnswer)){ echo $actionAnswer; }?>
		</span>
	</div>

		<div class="clearfix"></div>

		<div class="form-actions<?php if($flagHideForm)echo ' hide';?>">
			<?php
				$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => 'Далее →',
			)); ?>
		</div>
	</div>
<?

$this->endWidget();
?>
