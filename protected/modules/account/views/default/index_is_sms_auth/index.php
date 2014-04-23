<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $sClientInfoRender
 * @var $sIdentifyRender
 * @var $sClientInfoView
 * @var $bIsPossibleGetLoan
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

<?php if ($bIsPossibleGetLoan) { ?>
	<div class="center">

		<?$this->widget('bootstrap.widgets.TbButton', array(
			'size'        => 'large',
			'label'       => 'Получить займ прямо сейчас',
			'url'         => Yii::app()->createUrl('account/history'),
			'htmlOptions' => array(
				'class' => 'btn-warning',
			)
		));?>
	</div>
	<br />
<?
}
$this->widget('application.modules.account.components.AppInfoWidget');
?>