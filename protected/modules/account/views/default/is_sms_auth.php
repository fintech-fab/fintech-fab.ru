<?php
/**
 * @var $this Controller
 */
if (Yii::app()->adminKreddyApi->getBalance() < 0) {
	$sBalanceMessage = '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
} else {
	$sBalanceMessage = '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
}
$sExpireToMessage = '<strong>Вернуть до:</strong> ' . Yii::app()->adminKreddyApi->getActiveLoanExpired() . '<br/>';

if (Yii::app()->adminKreddyApi->getActiveLoanExpired()) {
	$sExpiredMessage = '<strong>Платеж просрочен!</strong><br/>';
} else {
	$sExpiredMessage = '';
}
?>
<h4><?= Yii::app()->adminKreddyApi->getClientFullName(); ?></h4>

<p>
	<?= $sExpiredMessage; ?>
	<?= $sBalanceMessage; ?>
	<?= $sExpireToMessage ?>
</p>