<?php
/* @var FormController $this */
/* @var ClientConfirmPhoneViaSMSForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 * @var integer $phone
 * @var bool    $flagSmsSent
 */

/*
 * Ввести код подтверждения из SMS
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Знакомство', 2),
	array('Заявка на займ', 5, 3)
);

?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

	<?php

	// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода
	$flagHideForm = (empty($flagSmsSent) || !empty($flagExceededTries));
	?>

	<div class="span10">
		Для завершения регистрации Вам нужно подтвердить свой телефон. <br /> Ваш телефон:
		<strong>+7<?php echo $phone; ?></strong> <br /><br />
		<?php
		// если SMS на телефон ещё не отсылалось
		if (empty($flagSmsSent)) {
			?>
			<div id="send_sms">
				<?php

				$form2 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
					'id'                     => get_class($oClientCreateForm) . '_sms',
					'enableClientValidation' => true,
					'clientOptions'          => array(
						'validateOnChange' => true,
						'validateOnSubmit' => true,
					),
					'action'                 => Yii::app()->createUrl('/form/ajaxsendsms'),
				));

				// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода
				$flagHideForm = (empty($flagSmsSent) || !empty($flagExceededTries));
				?>
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'sendSms',
					'buttonType'  => 'ajaxSubmit',
					'url'         => Yii::app()->createUrl('form/ajaxsendsms'),
					'size'        => 'small',
					'label'       => 'Отправить на +7' . Yii::app()->clientForm->getSessionPhone() . ' SMS с кодом подтверждения',
					'ajaxOptions' => array(
						'dataType' => "json",
						'type'     => "POST",
						'success'  => "function(data)
                                {
									$('#actionAnswer').html(data.text).hide();
                                	if(data.type==0)
                                	{
                                	    $('#send_sms').hide();
                                		$('#sms_code_row').show();
                                		$('.form-actions').show();
                               			$('#alertsmssent').fadeIn();
                                	}
                                	else if(data.type==1)
                                	{
                                	    $('#send_sms').hide();
                                		$('#sms_code_row').show();
                                		$('.form-actions').show();
                               			$('#actionAnswer').html(data.text).show();
                                	}
                                	else if(data.type==2)
                                	{
                                	    $('#send_sms').hide();
                                		$('#actionAnswer').html(data.text).show();
                               		}
                               		else if(data.type==3)
                                	{
                                        $('#actionAnswer').html(data.text).show();
                                	}
                                } ",
					),
				)); ?>
				<?

				$this->endWidget();
				?>
			</div>
		<?php } ?>
	</div>

	<?php

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($oClientCreateForm),
		'enableClientValidation' => true,
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl('/form/checksmscode'),
	));

	// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода
	$flagHideForm = (empty($flagSmsSent) || !empty($flagExceededTries));
	?>

	<div class="span10<?php if ($flagHideForm) {
		echo ' hide';
	} ?>" id="sms_code_row">
		<?php Yii::app()->user->setFlash('success', Dictionaries::C_SMS_SUCCESS); ?>
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'       => true, // display a larger alert block?
			'fade'        => false, // use transitions?
			'closeText'   => '&times;', // close link text - if set to false, no close link is displayed
			'htmlOptions' => array('style' => 'display:none;', 'id' => 'alertsmssent'),
		)); ?>
		<label>Введите код из SMS:</label>
		<?php echo $form->textField($oClientCreateForm, 'sms_code', array('class' => 'span4')); ?>
		<?php echo $form->error($oClientCreateForm, 'sms_code'); ?>
	</div>
		<span class="span10 help-block error<?php if (empty($actionAnswer)) {
			echo " hide";
		} ?>" id="actionAnswer">
			<?php if (!empty($actionAnswer)) {
				echo $actionAnswer;
			} ?>
		</span>


	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions<?php if ($flagHideForm) {
			echo ' hide';
		} ?>">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее →',
			)); ?>
		</div>
	</div>
	<?

	$this->endWidget();
	?>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>

</div>
