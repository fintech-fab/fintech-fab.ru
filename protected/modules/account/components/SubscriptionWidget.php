<?php

Yii::import('application.modules.account.components.BaseLoanSubscriptionWidget');

/**
 * Class SubscriptionWidget
 *
 */
class SubscriptionWidget extends BaseLoanSubscriptionWidget
{
	protected $sTitle = 'Подключение Пакета';
	protected $sSubscribeButtonLabel = 'Подключить Пакет';
	protected $sInfoTitle = 'Информация о подключении';
	protected $sNeedSmsMessage = 'Для подключения Пакета требуется подтверждение одноразовым SMS-кодом';
	protected $sNeedBankCardMessage = 'У Вас нет доступных каналов для получения займа! Требуется привязать к аккаунту банковскую карту.';
	protected $sAddBankCardButtonLabel = 'Привязать банковскую карту';
	protected $sNeedIdentifyHeader = 'Требуется идентификация';
	protected $sNeedIdentifyMessage = 'Для подключения пакета займов, необходимо пройти идентификацию.';
	protected $sSelectChannelHeader = 'Выберите канал получения займа';


	protected $sWidgetViewsPath = 'SubscriptionWidget';

	/**
	 * @return mixed
	 */
	protected function getNeedIdentifyHeader()
	{
		return $this->sNeedIdentifyHeader;
	}

	/**
	 * @return mixed
	 */
	protected function getSelectChannelHeader()
	{
		return $this->sSelectChannelHeader;
	}

	/**
	 * @return string
	 */
	protected function getProductInfo()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

		return 'Ваш пакет займов - &quot;'
		. Yii::app()->adminKreddyApi->getProductNameById($iProductId)
		. '&quot;<br /> Размер первого займа -'
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

		$iPacketSize = Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) *
			Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId);

		ob_start();
		?>
		<ul>
			<li>
				<strong>Пакет:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductNameById($iProductId) ?>
			</li>
			<li><strong>Сумма
					займа:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
				&nbsp;рублей
			</li>
			<li><strong>Количество займов в
					Пакете:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanCountById($iProductId) ?></li>
			<li>
				<strong>Размер пакета:</strong>&nbsp;<?= $iPacketSize; ?>&nbsp;рублей
			</li>
			<li><strong>Срок
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanLifetimeById($iProductId) ?>
				&nbsp;дней
			</li>
			<li>
				<strong>Способ получения
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
			</li>
			<li><strong>Стоимость
					подключения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId, $iChannelId) ?>
				&nbsp;рублей
			</li>
			<li><strong>Срок действия
					подключения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>
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

	/**
	 * @return string
	 */
	protected function getSubscribeButtonLabel()
	{
		return $this->sSubscribeButtonLabel;
	}

	/**
	 * @return string
	 */
	protected function getNeedBankCardMessage()
	{
		return $this->sNeedBankCardMessage;
	}

	/**
	 * @return string
	 */
	protected function getAddBankCardButtonLabel()
	{
		return $this->sAddBankCardButtonLabel;
	}

	/**
	 * @return string
	 */
	protected function getNeedIdentifyMessage()
	{
		return $this->sNeedIdentifyMessage;
	}
}

