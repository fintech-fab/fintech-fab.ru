<?php
/**
 * @var $sMessage
 * @var $oChangeAutoDebitingSettingForm ChangeAutoDebitingSettingForm
 * @var $form                           IkTbActiveForm
 * @var $this                           DefaultController
 */

$this->pageTitle = Yii::app()->name . " - Привязка банковской карты";
?>
	<h4>Привязка банковской карты</h4>

	<div class="alert alert-success in block"><?= $sMessage ?></div>

<?php if (!$oChangeAutoDebitingSettingForm->flag_enable_auto_debiting) { ?>
	<p>Для твоего удобства мы предлагаем включить АВТОСПИСАНИЕ задолженности с твоей банковской карты.</p>
	<p>В день окончания действия сервиса мы автоматически спишем с твоей банковской карты сумму задолженности.</p>
	<p>Тебе лишь нужно позаботиться о том, чтобы на карте имелась необходимая сумма.</p>
	<p><a href="<?= $this->createUrl('/account/changeAutoDebitingSetting') ?>">Ты всегда сможешь отключить опцию в своем
			Личном кабинете.</a></p>
	<div class="center">
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
					array('value' => 1)); ?>
			</div>
		</div>
		<br>

		<div class="clearfix"></div>
		<div>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'size'       => 'normal',
				'label'      => 'Включить автосписание',
			)); ?>
		</div>
		<?php
		$this->endWidget(); ?>
	</div>
<?php } ?>

	<div class="clearfix"></div>

<?php if (Yii::app()->adminKreddyApi->checkSubscribe() && !SiteParams::getIsIvanovoSite()): ?>
	<div class="well">
		<?php    $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Подключить сервис', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
					->createUrl('account/subscribe'),
		));?>
	</div>
<?php endif; ?>

<?php if (Yii::app()->adminKreddyApi->checkSubscribe() && SiteParams::getIsIvanovoSite()): ?>
	<div class="well">
		<?php    $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Получить деньги', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
					->createUrl('account/subscribe'),
		));?>
	</div>
<?php endif; ?>


<?php if (Yii::app()->adminKreddyApi->checkLoan()) { ?>
	<div class="well">
		<?php    $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Получить деньги', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
					->createUrl('account/loan'),
		));?>
	</div>
<?php } ?>
