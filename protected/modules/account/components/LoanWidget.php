<?php

Yii::import('application.modules.account.components.BaseLoanSubscriptionWidget');

/**
 * Class LoanWidget
 *
 */
class LoanWidget extends BaseLoanSubscriptionWidget
{
	protected $sTitle = 'Оформление займа';
	protected $sInfoTitle = 'Информация о займе';
	protected $sSelectChannelMessage = 'Выберите канал получения займа';
	protected $sNeedSmsMessage = 'Для оформления займа требуется подтверждение одноразовым SMS-кодом';
	protected $sSendSmsButtonLabel = 'Отправить SMS с кодом подтверждения на номер +7';

	protected $sWidgetViewsPath = 'LoanWidget';

	/**
	 *
	 */
	protected function setTitle()
	{
		$this->controller->pageTitle = Yii::app()->name . ' - ' . $this->sTitle;
	}

	/**
	 * @return string
	 */
	protected function getHeader()
	{
		return $this->sTitle;
	}

	/**
	 * @return string
	 */
	protected function getLoanInfo()
	{
		return 'Ваш пакет займов - &quot;'
		. Yii::app()->adminKreddyApi->getSubscriptionProduct()
		. '&quot;<br /> Размер второго займа - '
		. Yii::app()->adminKreddyApi->getSubscriptionLoanAmount()
		. '&nbsp;руб.';
	}

	/**
	 * @return string
	 */
	protected function getSelectChannelMessage()
	{
		return $this->sSelectChannelMessage;
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
				<strong>Пакет:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?>
			</li>
			<li><strong>Сумма займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?>&nbsp;рублей
			</li>
			<li><strong>Срок займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanLifetime() ?>&nbsp;дней
			</li>
			<li><strong>Способ получения
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
			</li>
			<li><strong>Время перечисления
					займа:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
			</li>
		</ul>
		<?php
		$this->renderChannelSpeedMessage();

		return ob_get_clean();
	}

}

