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

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => "checkCodes",
	'enableClientValidation' => true,
	'htmlOptions'            => array(
		'class' => "span10",
	),
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action' => Yii::app()->createUrl('/form/checkCodes'),
));
?>
<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<div id="alertsmssent" class="alert in alert-success">
	<?= Dictionaries::C_SMS_SUCCESS_NUM; ?>
	&nbsp;+7<?= Yii::app()->clientForm->getSmsSentPhone(); ?><br />
	<?= Dictionaries::C_EMAIL_SUCCESS; ?>
	&nbsp;<?= Yii::app()->clientForm->getSessionEmail(); ?>
</div>


<div class="clearfix"></div><label>Введите код из SMS:</label>
<?= $form->textField($oClientCreateForm, 'sms_code', array('class' => 'span4')); ?>
<?= $form->error($oClientCreateForm, 'sms_code'); ?>
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
<div class="clearfix"></div>    <label>Введите код из электронного письма:</label>
<?= $form->textField($oClientCreateForm, 'email_code', array('class' => 'span4')); ?>
<?= $form->error($oClientCreateForm, 'email_code'); ?>
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

	<div class="form-actions row">
		<div class="span2 offset1">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'backButton',
				'buttonType' => 'link',
				'url'        => Yii::app()
						->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label'      => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="span2 offset2">

			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
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
	После ввода кодов из SMS-сообщения и электронного письма вы попадете в личный кабинет. Для продолжения регистрации
	потребуется заполнить анкету клиента.
</div>
