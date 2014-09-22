<?php
/* @var FormController $this */
/* @var ClientConfirmPhoneAndEmailForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 */

/*
 * Ввести код подтверждения из SMS и e-mail
 */
?>
<h4>Подтверждение контактных данных</h4>
<div id="alertsmssent" class="alert in alert-success">
	<?= Dictionaries::C_SMS_SUCCESS_NUM; ?>
	&nbsp;+7<?= Yii::app()->clientForm->getSmsSentPhone(); ?><br />
	<?= Dictionaries::C_EMAIL_SUCCESS; ?>
	&nbsp;<?= Yii::app()->clientForm->getSessionEmail(); ?>
</div>
<div class="clearfix"></div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                     => "checkCodes",
	'enableClientValidation' => true,
	'type'                   => 'vertical',
	'htmlOptions'            => array(//'class' => "span10",
	),
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'                 => Yii::app()->createUrl('/form/checkCodes'),
));
?>
<?php $this->widget('YaMetrikaGoalsWidget'); ?>
<div class="clearfix"></div>
<div class="form-group">
	<label>Введите код из SMS:</label>

	<div class="col-lg-12">
		<?= $form->textField($oClientCreateForm, 'sms_code', array('class' => 'check_input form-control')); ?>
		<?= $form->error($oClientCreateForm, 'sms_code'); ?>
	</div>
</div>
<?php
$this->widget('application.modules.account.components.ResendCodeWidget',
	array(
		'sUrl'        => '/form/resendSms',
		'sId'         => 'Sms',
		'sResendText' => 'Повторно запросить SMS с кодом можно через:',
		'iTime'       => $this->getResendTime('sms'),
	)
);
?>
<br />

<div class="clearfix"></div>
<div class="form-group">
	<label>Введи код из электронного письма:</label>

	<div class="col-lg-12">
		<?= $form->textField($oClientCreateForm, 'email_code', array('class' => 'check_input form-control')); ?>
		<?= $form->error($oClientCreateForm, 'email_code'); ?>
	</div>
</div>
<?php
$this->widget('application.modules.account.components.ResendCodeWidget',
	array(
		'sUrl'        => '/form/resendEmail',
		'sId'         => 'Email',
		'sResendText' => 'Повторно запросить e-mail с кодом можно через:',
		'iTime'       => $this->getResendTime('email'),
	)
);
?>
<br />
<div class="clearfix"></div>
<div class="span12">

	<div class="form-group row">
		<div class="col-xs-1 col-xs-offset-1">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'backButton',
				'buttonType' => 'link',
				'type'       => 'primary',
				'url'        => Yii::app()
				->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label'      => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="col-xs-1 col-xs-offset-3">

			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'success',
				'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>


	<?php
	$this->endWidget();
	?>
</div>

<div class="clearfix"></div>

<div class="alert in alert-warning" style="font-size: 12pt;">
	После ввода кодов из SMS-сообщения и электронного письма ты попадешь в личный кабинет. Для продолжения регистрации
	потребуется заполнить анкету клиента.
</div>
