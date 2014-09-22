<?php
/**
 * @var SubscriptionWidget $this
 * @var SMSCodeForm        $oModel
 * @var IkTbActiveForm     $form
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

<?php
$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
?>
	<div class="alert in alert-block alert-warning">
		<?= $this->getNeedSmsMessage(); ?>
	</div>
<?php
$this->widget('SmsCodeWidget', array(
	'oModel'        => $oModel,
	'sType'         => SmsCodeComponent::C_TYPE_SUBSCRIBE,
	'oSmsComponent' => Yii::app()->smsCode,
));

