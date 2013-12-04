<?php
/**
 * Class ClientDataSenderCommand
 */
class ClientDataSenderCommand extends CConsoleCommand
{
	/**
	 * Тело отправляемого письма: ФИО, E-mail и телефон клиентов, не до конца заполнивших анкету
	 *
	 * @var string
	 */
	public $sClientsInfo = '';

	public function actionSendClientData()
	{
		// выбираем только за последний день тех, кого ещё не "чистили"
		$sSql = "SELECT * from `tbl_client` WHERE `flag_sms_confirmed` = '0'
		AND `dt_update` >= TIMESTAMP((NOW() - INTERVAL 1 DAY))
		AND `last_name` != '*'
		AND `first_name` != '*'
		AND `third_name` != '*'
		";

		$oDataReader = Yii::app()->db->createCommand($sSql)->query();
		$oClient = $oDataReader->read();

		while ($oClient !== false) {
			$sLastName = (!empty($oClient['last_name'])) ? $oClient['last_name'] : '';
			$sFirstName = (!empty($oClient['first_name'])) ? $oClient['first_name'] : '';
			$sThirdName = (!empty($oClient['third_name'])) ? $oClient['third_name'] : '';
			$sEmail = (!empty($oClient['email'])) ? $oClient['email'] : '';
			$sPhone = (!empty($oClient['phone'])) ? $oClient['phone'] : '';

			$this->sClientsInfo .= "Телефон: $sPhone\r\nE-mail: $sEmail\r\nФИО: $sLastName $sFirstName $sThirdName \r\n\r\n=================================================\r\n\r\n";

			$oClient = $oDataReader->read();
		}

		$sEmail = SiteParams::getContactEmail();
		$sSubject = "Анкеты, не до конца заполненные Клиентами";

		EmailComponent::sendEmail($sEmail, $sSubject, $this->sClientsInfo, SiteParams::getEmailFrom());
	}
}
