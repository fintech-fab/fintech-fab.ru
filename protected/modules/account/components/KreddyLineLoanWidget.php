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
		return 'Сумма перевода - ' . Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() . '&nbsp;руб.';
	}

	/**
	 * @return string
	 */
	protected function getFullInfo()
	{
		$iChannelId = Yii::app()->adminKreddyApi->getLoanSelectedChannel();

		$iPercentDaily = round(Yii::app()->adminKreddyApi->getCurrentClientProduct()['percent_daily']);

		ob_start();
		?>

		<ul>
			<li>
				<strong>Доступная сумма:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?>
				&nbsp;р.
			</li>
			<li>
				<strong>Стоимость пользования деньгами:</strong>&nbsp;<?= $iPercentDaily ?>&nbsp;р. в день
			</li>
			<li>
				<strong>Количество переводов в течение действия сервиса:</strong>&nbsp;не чаще 1 раза в сутки, не более 5 переводов в месяц
			</li>
			<li>
				<strong>Срок возврата денег:</strong>&nbsp;в течение
				<?= Yii::app()->adminKreddyApi->getSubscriptionLoanLifetime() ?> дн. с момента подтверждения условий
			</li>
			<li>
				<strong>Сервис действует
					до:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionActivityToTime() ?>
			</li>
			<li><strong>Время перечисления
					денег:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
			</li>
			<li>
				<strong>Способ перечисления денег:</strong>&nbsp;
				<?=
				(Yii::app()->adminKreddyApi->isSelectedChannelBankCard())
					? 'на банковскую карту'
					: 'на мобильный телефон'
				?>
			</li>
		</ul>
		<?php
		$this->renderChannelSpeedMessage();

		return ob_get_clean();
	}
}