<?php

class SessionExpireTimeWidget extends CWidget
{
	// сообщение, если сессия истекла
	public $sExpiredMessage = AdminKreddyApiComponent::C_SESSION_EXPIRED;

	// текст перед оставшимся временем
	public $sLeftTimeMessage = AdminKreddyApiComponent::C_SESSION_TIME_UNTIL_EXPIRED;

	//  время жизни сессии (в минутах)
	public $iLiveTimeMinutes = AdminKreddyApiComponent::TOKEN_MINUTES_LIVE;

	public function run()
	{
		$this->render('session_expire_time_widget');
	}
}
