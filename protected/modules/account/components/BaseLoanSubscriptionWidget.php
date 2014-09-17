<?php

/**
 * Class BaseLoanSubscriptionWidget
 */
class BaseLoanSubscriptionWidget extends CWidget
{
	protected $sTitle = '';
	protected $sNeedSmsMessage = '';
	protected $sInfoTitle = '';

	protected $sWidgetViewsPath = '';

	public $oModel;
	public $sView;

	/**
	 *
	 */
	public function run()
	{
		$this->setTitle();

		$this->render($this->sWidgetViewsPath . "/" . $this->sView, array('oModel' => $this->oModel));
	}

	public function getWidgetViewsPath()
	{
		return $this->sWidgetViewsPath;
	}

	/**
	 * @return string
	 */
	protected function getHeader()
	{
		return $this->sTitle;
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
	protected function getNeedSmsMessage()
	{
		return $this->sNeedSmsMessage;
	}

	/**
	 * @return string
	 */
	protected function getInfoTitle()
	{
		return $this->sInfoTitle;
	}

	/**
	 * заглушка-прототип функци
	 */
	protected function getFullInfo()
	{

	}

	/**
	 * Отображаем форму запроса СМС-кода
	 */
	protected function renderSmsPassForm()
	{
		$oSmsPassForm = new SMSCodeForm('sendRequired');
		//TODO переделать более правильно
		$this->controller->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), false, false);
	}

	/**
	 * Отображаем сообщение о сроках зачисления средств в случае, если выбрана в качестве канала банковская карта
	 */
	protected function renderChannelSpeedMessage()
	{
		if (Yii::app()->adminKreddyApi->isSelectedChannelBankCard()) {
			?>
			<p><i>Срок зачисления средств зависит от банка, выпустившего карту. Многие банки зачисляют средства на
					банковские карты в течение 15-30 минут с момента перевода. В некоторых случаях срок зачисления может
					составить несколько дней.</i></p>

		<?php
		}
	}

} 