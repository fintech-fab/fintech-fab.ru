<?php
/**
 * @var array $aPaymentData
 */

$this->pageTitle = Yii::app()->name . ' - График платежей';

if (!Yii::app()->adminKreddyApi->isSubscriptionActive()) {
	$aPaymentData['subscription_balance'] = 0;
}

$sSubscriptionEndDt = SiteParams::formatRusDate(SiteParams::getDayEndFromDatetime($aPaymentData['subscription_expired_to']));
$sLoanEndDt = SiteParams::formatRusDate(SiteParams::getDayEndFromDatetime($aPaymentData['loan_expired_to']));

$iSubscriptionTotal = $aPaymentData['subscription_balance'] + $aPaymentData['penalty_balance'];
$iLoanTotal = $aPaymentData['loan_balance'] + $aPaymentData['percent_balance'] + $aPaymentData['fine_balance'];

$iTotal = $iLoanTotal + $iSubscriptionTotal;


?>

	<h4>График платежей</h4>

	<div class="alert alert-success">
		<h5>Ты всегда можешь вернуть деньги в любое удобное время</h5>
		<h5>Пользуйся деньгами столько, сколько тебе нужно в период действия подключения!</h5>
	</div>

	<div class="alert alert-warning">
		<h5><strong>Общая сумма к возврату: <?= $iTotal ?>
				руб.</strong> <a href="#" class="dotted" id="detail" onclick="return false;">детализация</a>
		</h5>

		<p style="display: none;" id="detail_info">
			- Абонентка: <?= $aPaymentData['subscription_balance'] ?> руб. <br /> -
			Штраф: <?= $aPaymentData['penalty_balance'] ?> руб. <br /> - Сумма
			перевода: <?= $aPaymentData['loan_balance'] ?> руб. <br /> -
			Проценты: <?= $aPaymentData['percent_balance'] ?> руб. <br /> - Неустойка: <?= $aPaymentData['fine_balance'] ?> руб. <br />
		</p>
	</div>

	<h4><strong>Оплата за сервис</strong></h4>
	<div class="alert alert-warning" style="float:left; width: 53%;">
		<h5><strong>Абонентская плата за сервис: <?= $aPaymentData['subscription_balance'] ?> руб.</strong></h5>
		<h5><strong>Штраф за просрочку: <?= $aPaymentData['penalty_balance'] ?> руб.</strong></h5>
	</div>
	<div class="alert alert-success" style="float:right; width: 28%;">
		<h5><strong>ИТОГО: <?= $iSubscriptionTotal ?> руб.</strong></h5>
		<h5><strong>Оплатить до: <br /><?= $sSubscriptionEndDt ?></strong></h5>
	</div>

	<div style="clear: both"></div>

<?php if ($iLoanTotal > 0) { ?>
	<h4><strong>Оплата за пользование деньгами</strong></h4>
	<div class="alert alert-warning" style="float:left; width: 53%;">
		<h5><strong>Мы перевели тебе: <?= $aPaymentData['loan_balance'] ?> руб.</strong></h5>
		<h5><strong>Каждый день ты платишь проценты: <?= $aPaymentData['percent_daily'] ?> руб.</strong></h5>
		<h5><strong>Ты пользуешься деньгами: <?= $aPaymentData['loan_days_usage'] ?> дн.</strong></h5>
		<h5><strong>На сегодняшний день начислено: <?= $aPaymentData['percent_balance'] ?> руб.</strong></h5>
		<h5><strong>Неустойка за просрочку: <?= $aPaymentData['fine_balance'] ?> руб.</strong></h5>
	</div>

	<div class="alert alert-success" style="float:right; width: 28%;">
		<h5><strong>ИТОГО: <?= $iLoanTotal ?> руб.</strong></h5>
		<h5><strong>Вернуть до: <br /><?= $sLoanEndDt ?></strong></h5>
	</div>
<?php
}

Yii::app()->clientScript->registerScript('detail_balance',
	"
	var content = $('#detail_info').html();
	$('#detail').popover({
		'selector': '',
		'placement': 'right',
		'content': content,
		'html': 'true'
	});
	"
);