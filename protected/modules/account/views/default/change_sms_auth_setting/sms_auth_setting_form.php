<?php
/* @var DefaultController $this */
/* @var ChangeSmsAuthSettingForm $oChangeSmsAuthSettingForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Настройка безопасности";
?>

	<h4>Настройка безопасности</h4>

	<p>Двухфакторная аутентификация
		<span class="bold"><?= ($oChangeSmsAuthSettingForm->sms_auth_enabled) ? 'включена' : 'выключена'; ?></span></p>

	<p>
		Ты можешь <?= ($oChangeSmsAuthSettingForm->sms_auth_enabled) ? 'выключить' : 'включить'; ?> дополнительную
		проверку входа в личный кабинет по смс-коду </p>

<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'sms-auth-setting-form',
	'enableAjaxValidation' => false,
	'type'                 => 'horizontal',
	'action'               => Yii::app()->createUrl('/account/changeSmsAuthSetting'),
));
?>

	<div class="row">
		<div class="span5">
			<?=
			$form->hiddenField(
				$oChangeSmsAuthSettingForm,
				'sms_auth_enabled',
				array('value' => ($oChangeSmsAuthSettingForm->sms_auth_enabled) ? 0 : 1)); ?>
		</div>
	</div>
	<br>
	<div class="clearfix"></div>
	<div>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'normal',
			'label'      => ($oChangeSmsAuthSettingForm->sms_auth_enabled) ? 'Выключить' : 'Включить',
		)); ?>
	</div>

<?php
$this->endWidget();