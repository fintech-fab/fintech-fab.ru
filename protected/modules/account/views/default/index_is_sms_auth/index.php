<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sClientInfoRender
 * @var $sIdentifyRender
 * @var $sClientInfoView
 * @var $bIsPossibleDoLoan
 * @var $bIsNeedSubscriptionConfirm
 * @var $bIsNeedSubscriptionPay
 * @var $bIsNeedLoanConfirm
 */

$this->breadcrumbs = array(
	$this->module->id,
);

?>

<?
if (Yii::app()->adminKreddyApi->isSubscriptionOldType()) {
	$this->widget('application.modules.account.components.ClientInfoWidget', array('sClientInfoView' => $sClientInfoView));
} else {
	$this->widget('application.modules.account.components.ClientKreddyLineInfoWidget', array('sClientInfoView' => $sClientInfoView));
}
?>
	<br />
<?= $sIdentifyRender ?>
	<br />

<?php if ($bIsPossibleDoLoan) { ?>
	<div class="center">

		<?$this->widget('bootstrap.widgets.TbButton', array(
			'size'        => 'large',
			'label'       => 'Получить деньги',
			'url'         => Yii::app()->createUrl('account/loan'),
			'htmlOptions' => array(
				'class' => 'btn-warning',
			)
		));?>
	</div>
	<br />
<?
}
if ($bIsNeedSubscriptionConfirm) {
	?>
	<div class="center">

		<?$this->widget('bootstrap.widgets.TbButton', array(
			'size'        => 'large',
			'label'       => 'Подключить КРЕДДИ',
			'url'         => Yii::app()->createUrl('account/doSubscribeConfirm'),
			'htmlOptions' => array(
				'class' => 'btn-warning',
			)
		));?>
	</div>
	<br />
<?
}
if ($bIsNeedSubscriptionPay) {
	?>
	<div class="center">
		<?$this->widget('bootstrap.widgets.TbButton', array(
			'size'        => 'large',
			'label'       => 'Оплатить',
			'url'         => Yii::app()->params['payUrl'],
			'htmlOptions' => array(
				'class' => 'btn-warning',
			)
		));?>
	</div>
	<br />
<?
}
if ($bIsNeedLoanConfirm) {
	?>
	<div class="alert">
		<h4>Тебе необходимо подтвердить условия получения денег</h4>
	</div>
	<div class="center">
		<?$this->widget('bootstrap.widgets.TbButton', array(
			'size'  => 'large',
			'label' => 'Перейти к условиям',
			'type'  => 'primary',
			'url'   => Yii::app()->createUrl('account/doLoanConfirm'),
		));?>
	</div>
	<br />
<?
}
$this->widget('application.modules.account.components.BannerWidget');
?>