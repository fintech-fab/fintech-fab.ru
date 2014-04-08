<?php

/**
 * Class KreddyLineLoanWidget
 */
class KreddyLineLoanWidget extends LoanWidget
{
	/**
	 * @return string
	 */
	protected function getLoanInfo()
	{
		return 'Ваша КРЕДДИтная линия - &quot;'
		. Yii::app()->adminKreddyApi->getSubscriptionProduct()
		. '&quot;<br /> Размер займа - '
		. Yii::app()->adminKreddyApi->getSubscriptionLoanAmount()
		. '&nbsp;руб.';
	}

	/**
	 * @return string
	 */
	protected function getFullInfo()
	{
		$iChannelId = Yii::app()->adminKreddyApi->getLoanSelectedChannel();

		ob_start();
		?>

		<ul>
			<li>
				<strong><?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?></strong>
			</li>
			<li><strong>Сумма займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?>&nbsp;рублей
			</li>
			<li><strong>Вернуть займ
					до:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionActivityToTime() ?>
			</li>
			<li><strong>Способ получения
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
			</li>
			<li><strong>Время перечисления
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
			</li>
		</ul>
		<p><i>Срок зачисления средств зависит от банка, выпустившего Вашу карту, и может составить от нескольких минут
				до нескольких дней. Многие банки зачисляют средства на банковские карты в течение 15-30 минут с момента
				перевода. В некоторых случаях срок зачисления может составить несколько дней.</i></p>
		<?php
		return ob_get_clean();
	}
}