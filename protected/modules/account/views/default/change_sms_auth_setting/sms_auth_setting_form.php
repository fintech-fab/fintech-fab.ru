<?php
/* @var DefaultController $this */
/* @var ChangeSmsAuthSettingForm $oChangeSmsAuthSettingForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Настройка безопасности";
?>

	<h4>Настройка безопасности</h4>

<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'sms-auth-setting-form',
	'enableAjaxValidation' => false,
	'type'                 => 'horizontal',
	'action'               => Yii::app()->createUrl('/account/changeSmsAuthSetting'),
));
?>

	<div class="row">
		<div class="span5">
			<?= $form->checkBoxRow($oChangeSmsAuthSettingForm, 'sms_auth_enabled'); ?>
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'normal',
			'label'      => 'Отправить',
		)); ?>
	</div>

<?php
$this->endWidget();