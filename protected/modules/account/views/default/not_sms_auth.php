<?php
/**
 * @var $this Controller
 */
if (Yii::app()->adminKreddyApi->getIsDebt()) {
	$sDebtMessage = 'У вас есть задолженность по кредиту.';
} elseif (isset($bIsDebt) && !$bIsDebt) {
	$sDebtMessage = 'У вас нет задолженности по кредиту.';
}
?>
<p><strong><?= $sDebtMessage ?></strong><br />Авторизуйтесь по SMS-паролю для получения подробной информации. </p>