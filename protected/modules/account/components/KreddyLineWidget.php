<?php

/**
 * Class KreddyLineWidget
 */
class KreddyLineWidget extends SubscriptionWidget
{
	protected $sTitle = 'Подключение КРЕДДИтной линии';
	protected $sSubscribeButtonLabel = 'Подключить КРЕДДИтную линию';
	protected $sNeedIdentifyMessage = 'Для подключения КРЕДДИтной линии необходимо пройти идентификацию.';
	protected $sNeedSmsMessage = 'Для подключения КРЕДДИтной лини требуется подтверждение одноразовым SMS-кодом';

	/**
	 * @return string
	 */
	protected function getProductInfo()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

		return 'Ваша КРЕДДИтная линия - &quot;'
		. Yii::app()->adminKreddyApi->getProductNameById($iProductId)
		. '&quot;<br /> Размер одного займа - '
		. Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) .
		'руб.';
	}

	/**
	 * @return string
	 */
	protected function getFullInfo()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
		$iChannelId = Yii::app()->adminKreddyApi->getSubscribeSelectedChannel();

		ob_start();
		?>
		<ul>
			<li>
				<strong><?= Yii::app()->adminKreddyApi->getProductNameById($iProductId) ?></strong>
			</li>
			<li><strong>Сумма
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
				&nbsp;рублей
			</li>
			<li><strong>Количество займов в КРЕДДИтной линии:</strong>&nbsp;не ограничено</li>

			<li><strong>Срок займа:</strong>&nbsp;от 1 до
				&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
				&nbsp;дней
			</li>

			<li>
				<strong>Способ получения
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
			</li>
			<li><strong>Размер абонентской
					платы:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId, $iChannelId) ?>
				&nbsp;рублей
			</li>
			<li><strong>Срок действия КРЕДДИтной
					линии:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
				&nbsp;дней
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