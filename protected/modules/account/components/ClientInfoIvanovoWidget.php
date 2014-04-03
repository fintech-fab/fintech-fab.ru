<?php

/**
 * Class ClientInfoWidget
 */
class ClientInfoIvanovoWidget extends ClientInfoWidget
{
	protected $sTitle = 'Статус займа';
	protected $sNoActiveProducts = 'Нет активных займов';
	protected $sSubscribeButtonLabel = 'Оформить займ';

	public function renderProductRequest()
	{
		?>
		<strong>Сумма займа:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequestLoan() ?><br />
	<?php
	}

	protected function renderProduct()
	{
		?>
		<strong>Сумма займа:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?><br />
	<?php

	}

}
