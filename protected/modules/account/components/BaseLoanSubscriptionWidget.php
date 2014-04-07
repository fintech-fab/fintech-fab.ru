<?php

/**
 * Class BaseLoanSubscriptionWidget
 */
class BaseLoanSubscriptionWidget extends CWidget
{
	protected $sTitle = '';
	protected $sNeedSmsMessage = '';
	protected $sInfoTitle = '';
	protected $sSendSmsButtonLabel = 'Отправить SMS с кодом подтверждения на номер +7';
	protected $sSentSmsSuccessMessage = 'Код подтверждения операции успешно отправлен по SMS на номер +7';
	protected $sSmsCodeConfirmButtonLabel = 'Подтвердить';
	protected $sEnterCodeMessage = 'Для подтверждения операции введите код, отправленный Вам по SMS';
	protected $sSendSmsErrorMessage = 'При отправке SMS с кодом подтверждения произошла ошибка.
		 Попробуйте снова запросить код подтверждения.<br />В случае, если ошибка повторяется,
		 обратитесь в контактный центр.';

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
	protected function getSendSmsButtonLabel()
	{
		return $this->sSendSmsButtonLabel . Yii::app()->user->getMaskedId();
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
	protected function getEnterCodeMessage()
	{
		return $this->sEnterCodeMessage;
	}

	protected function renderSendSmsButton()
	{
		?>
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'size'       => 'small',
				'label'      => $this->getSendSmsButtonLabel(),
			)); ?>
		</div>
	<?php
	}

	/**
	 * @return string
	 */
	protected function getSentSmsSuccessMessage()
	{
		return $this->sSentSmsSuccessMessage . Yii::app()->user->getMaskedId();
	}

	/**
	 * @return string
	 */
	protected function getSmsCodeConfirmButtonLabel()
	{
		return $this->sSmsCodeConfirmButtonLabel;
	}

	protected function renderSmsCodeSubmitButton()
	{
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => $this->getSmsCodeConfirmButtonLabel(),
		));
	}

	/**
	 * @return string
	 */
	protected function getSendSmsErrorMessage()
	{
		return $this->sSendSmsErrorMessage;
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
		$oSmsPassForm = new SMSPasswordForm('sendRequired');
		//TODO переделать более правильно
		$this->controller->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), false, false);
	}

} 