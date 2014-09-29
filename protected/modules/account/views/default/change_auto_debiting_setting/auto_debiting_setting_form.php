<?php
/* @var DefaultController $this */
/* @var ChangeAutoDebitingSettingForm $oChangeAutoDebitingSettingForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Настройка профиля";
?>

	<h4>Настройка профиля</h4>

	<p>Автосписание
		<span class="bold"><?= ($oChangeAutoDebitingSettingForm->flag_enable_auto_debiting) ? 'включено' : 'выключено'; ?></span>
	</p>

	<p>
		Ты можешь <?= ($oChangeAutoDebitingSettingForm->flag_enable_auto_debiting) ? 'выключить' : 'включить'; ?>
		автоматическое списание задолженности с привязанной банковской карты в последний день действия сервиса </p>

<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'sms-auth-setting-form',
	'enableAjaxValidation' => false,
	'type'                 => 'horizontal',
	'action'               => Yii::app()->createUrl('/account/changeAutoDebitingSetting'),
));
?>

	<div class="row">
		<div class="span5">
			<?=
			$form->hiddenField(
				$oChangeAutoDebitingSettingForm,
				'flag_enable_auto_debiting',
				array('value' => ($oChangeAutoDebitingSettingForm->flag_enable_auto_debiting) ? 0 : 1)); ?>
		</div>
	</div>
	<br>
	<div class="clearfix"></div>
	<div>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'normal',
			'label'      => ($oChangeAutoDebitingSettingForm->flag_enable_auto_debiting) ? 'Выключить' : 'Включить',
		)); ?>
	</div>

<?php
$this->endWidget();