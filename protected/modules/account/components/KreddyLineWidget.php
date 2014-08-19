<?php

/**
 * Class KreddyLineWidget
 */
class KreddyLineWidget extends SubscriptionWidget
{
	protected $sTitle = 'Подключение КРЕДДИ';
	protected $sSubscribeButtonLabel = 'Продолжить';
	protected $sNeedIdentifyMessage = 'Для подключения КРЕДДИ необходимо пройти идентификацию.';
	protected $sNeedSmsMessage = 'Для подключения КРЕДДИ требуется подтверждение одноразовым SMS-кодом';

	/**
	 * @return string
	 */
	protected function getFullInfo()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscriptionProductId();
		if (!$iProductId) {
			$iProductId = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
		}

		ob_start();
		?>
		<ul>
			<li>
				<strong>Сумма:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductLoanAmountById($iProductId) ?>
				&nbsp;рублей
			</li>
			<li>
				<strong>Размер абонентской
					платы:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getProductCostById($iProductId) ?>
				&nbsp;рублей
			</li>
			<li>
				<strong>Срок действия:</strong>&nbsp;
				<?= Yii::app()->adminKreddyApi->getProductLifetimeById($iProductId) ?>&nbsp;дн.
			</li>
			<li>
				<strong>Условия внесения абонентской
					платы:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPaymentRuleByProduct($iProductId); ?>
			</li>
		</ul>
		<?php
		return ob_get_clean();

	}
} 