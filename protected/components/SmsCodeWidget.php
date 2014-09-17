<?php

class SmsCodeWidget extends CWidget
{
	/**
	 * @var SmsCodeComponent $oSmsComponent
	 */
	public $oSmsComponent;

	public $sType;

	public $oModel;

	public $sForceState;

	private $sState;

	public function run()
	{
		$this->setState();

		if ($this->sState == SmsCodeComponent::C_STATE_NEED_SEND || $this->sState == SmsCodeComponent::C_STATE_NEED_CHECK_OK) {
			$this->oModel->sendSmsCode = 1;
			$this->render('sms_code/send', array(
				'oModel'  => $this->oModel,
				'sAction' => $this->oSmsComponent->getActionByType($this->sType),
			));
		} else {
			$this->render('sms_code/check', array(
				'oModel'  => $this->oModel,
				'sAction' => $this->oSmsComponent->getActionByType($this->sType),
				'sType'   => $this->sType,
			));
		}
	}

	private function setState()
	{
		if ($this->sForceState) {
			$this->sState = $this->sForceState;

			return;
		}

		$this->sState = $this->oSmsComponent->getState($this->sType);
	}
}