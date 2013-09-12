<?php
/* @var FormController $this */
/* @var ClientConfirmPhoneViaSMSForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 * @var integer $phone
 * @var bool    $flagSmsSent
 * @var bool    $flagExceededTries
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

// поле ввода кода и кнопку "далее" прячем, если не отправлено смс или исчерпаны все попытки ввода
$flagHideFormCheckSMSCode = empty($flagSmsSent) || !empty($flagExceededTries);
?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

	<div class="span10">
		Для завершения регистрации Вам необходимо подтвердить свой телефон. <br /> Ваш телефон:
		<strong>+7<?= $phone; ?></strong> <br /><br />

		<?php
		// если SMS на телефон ещё не отсылалось
		if (empty($flagSmsSent)) {
			$form2 = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                     => get_class($oClientCreateForm) . '_ajaxsendsms',
				'enableClientValidation' => true,
				'clientOptions'          => array(
					'validateOnChange' => true,
					'validateOnSubmit' => true,
				),
				'action'                 => Yii::app()->createUrl('/form/ajaxsendsms'),
			));

			$this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'sendSms',
				'buttonType'  => 'ajaxSubmit',
				'url'         => Yii::app()->createUrl('form/ajaxsendsms'),
				'size'        => 'small',
				'label'       => 'Отправить на +7' . Yii::app()->clientForm->getSessionPhone() . ' SMS с кодом подтверждения',
				'ajaxOptions' => array(
					'dataType' => "json",
					'type'     => "POST",
					'success'  => "function(data) {
									$('#actionAnswer').html(data.text).hide();
                                	if(data.type == 0) { /* SMS успешно отправлено */
                                	    $('#" . get_class($oClientCreateForm) . "_ajaxsendsms').hide();
                                		$('#" . get_class($oClientCreateForm) . "_checksmscode').show();
                               			$('#alertsmssent').fadeIn();
                               		} else if(data.type == 2) { /* Общая ошибка */
                                	    $('#" . get_class($oClientCreateForm) . "_ajaxsendsms').hide();
                                		$('#actionAnswer').html(data.text).show();
                                	} else if(data.type == 1) { /* Ошибка - SMS уже было отправлено */
                                	    $('#" . get_class($oClientCreateForm) . "_ajaxsendsms').hide();
                                		$('#" . get_class($oClientCreateForm) . "_checksmscode').show();
                               			$('#actionAnswer').html(data.text).show();
                               		} else if(data.type == 3) { /* Ошибка при отправке SMS */
                                        $('#actionAnswer').html(data.text).show();
                                	}
                                } ",
				),
			));

			$this->endWidget();
		} ?>
	</div>

	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                     => get_class($oClientCreateForm) . "_checksmscode",
		'enableClientValidation' => true,
		'htmlOptions'            => array(
			'class' => "span10" . ($flagHideFormCheckSMSCode ? ' hide' : ''),
		),
		'clientOptions'          => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'                 => Yii::app()->createUrl('/form/checksmscode'),
	));
	?>
	<div id="alertsmssent" class="alert in alert-success hide"><?= Dictionaries::C_SMS_SUCCESS; ?></div>

	<label>Введите код из SMS:</label>
	<?= $form->textField($oClientCreateForm, 'sms_code', array('class' => 'span4')); ?>
	<?= $form->error($oClientCreateForm, 'sms_code'); ?>

	<div class="help-block error<?= empty($actionAnswer) ? ' hide' : ''; ?>" id="actionAnswer">
		<?php if (!empty($actionAnswer)) {
			echo $actionAnswer;
		} ?>
	</div>

	<div class="clearfix"></div>

	<div class="row span11">
		<div class="form-actions">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее →',
			)); ?>
		</div>
	</div>
	<?php
	/**
	 * конец формы проверки кода
	 */
	$this->endWidget();
	?>

	<?php
	// если исчерпано число попыток, то форма ввода кода не показывается, но нужно вывести сообщение
	if (!empty($actionAnswer) && !empty($flagExceededTries)) {
		echo '
	<div class="help-block error span10" id="exceededTries">
		' . $actionAnswer . '
	</div>
	';
	} ?>
	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>
</div>
