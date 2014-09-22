<?php

Yii::import('application.modules.account.components.BaseLoanSubscriptionWidget');

/**
 * Class LoanWidget
 *
 */
class LoanWidget extends BaseLoanSubscriptionWidget
{
	protected $sTitle = 'Получить деньги';
	protected $sInfoTitle = 'Информация';
	protected $sSelectChannelMessage = 'Выбери канал';
	protected $sNeedSmsMessage = 'Для оформления запроса на перевод требуется подтверждение одноразовым SMS-кодом';

	protected $sWidgetViewsPath = 'LoanWidget';

	/**
	 * Получение блока с индивидуальными условиями
	 *
	 * @return string
	 */
	public function getIndividualConditionInfo()
	{

		$aConditions = Yii::app()->adminKreddyApi->getIndividualConditionList();

		if (!isset($aConditions['active'])) {
			return '';
		}
		$aActiveCondition = $aConditions['active'];

		ob_start();
		?>
		<div class="alert alert-info">
			<h4>Для тебя подготовлены</h4>
			<h5><strong>Индивидуальные условия:</strong> № <?= $aActiveCondition['contract_number'] ?></h5>

			<p><strong>подтвердить до:</strong> <?= SiteParams::formatRusDate($aActiveCondition['dt_confirm_to']) ?></p>

			<p>(<?=
				CHtml::link(
					'посмотреть',
					Yii::app()->createUrl('/account/getDocument', ['id' => $aActiveCondition['hash']]),
					array('target' => '_blank')
				)
				?>)</p>
		</div>
		<?php

		return ob_get_clean();
	}


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
				<strong>Доступная сумма:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanAmount() ?>
				&nbsp;рублей
			</li>
			<li><strong>Срок пользования
					деньгами:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getSubscriptionLoanLifetime() ?>&nbsp;дн.
			</li>
			<li><strong>Способ получения
					денег:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameById($iChannelId) ?>
			</li>
			<li><strong>Время перечисления
					денег:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getChannelSpeed($iChannelId); ?>
			</li>
		</ul>
		<?php
		$this->renderChannelSpeedMessage();

		return ob_get_clean();
	}

}

