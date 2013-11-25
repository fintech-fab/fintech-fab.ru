<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.novikov && e.zamorskaya
 * Date: 15.04.13
 * Time: 16:25
 * To change this template use File | Settings | File Templates.
 *
 * @property CFormModel|ClientCreateFormAbstract $owner
 */

class FormFieldValidateBehavior extends CBehavior
{
	/**
	 * проверка имени клиента
	 * @param string $attribute
	 * @param array  $param
	 */
	public function checkValidClientName($attribute, $param)
	{
		if ($this->owner->$attribute) {
			if (!preg_match('/^[-а-яё\s]+$/ui', $this->owner->$attribute)) {
				$this->owner->addError($attribute, $param['message']);
			} else {
				$this->formatName($this->owner->$attribute);
			}
		}
	}

	/**
	 * проверка ФИО
	 * @param string $attribute
	 * @param array  $param
	 */
	public function checkValidFio($attribute, $param)
	{
		if ($this->owner->$attribute) {
			if (!preg_match('/^[-а-яё \s]+$/ui', $this->owner->$attribute)) {
				$this->owner->addError($attribute, $param['message']);
			} else {
				$this->formatName($this->owner->$attribute);
			}
		}
	}

	/**
	 * проверка телефона
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientPhone($attribute, $param)
	{
		if ($this->owner->$attribute) {
			//очистка данных
			$this->owner->$attribute = ltrim($this->owner->$attribute, '+ ');
			$this->owner->$attribute = preg_replace('/[^\d]/', '', $this->owner->$attribute);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->owner->$attribute) == 11) {
				$this->owner->$attribute = substr($this->owner->$attribute, 1, 10);
			}

			if (strlen($this->owner->$attribute) !== SiteParams::C_PHONE_LENGTH) {
				$this->owner->addError($attribute, $param['message']);
			}
		}
	}

	/*public function findClientStatusIdByExtraAttribute( $attribute )
	{
		//	проверим alias на уникальность
		$oClient = Client::model()->scopeUniqueExtra( $attribute, $this->owner->$attribute )->find();
		return empty( $oClient )? false: $oClient;
	}*/

	/**
	 * проверка цифрового кода
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientNumericCode($attribute, $param)
	{
		//очистка данных
		$this->owner->$attribute = trim($this->owner->$attribute);
		$this->owner->$attribute = preg_replace('/\s+/', '', $this->owner->$attribute);
		if (strlen($this->owner->$attribute) < SiteParams::C_NUMERIC_CODE_MIN_LENGTH || strlen($this->owner->$attribute) > SiteParams::C_NUMERIC_CODE_MAX_LENGTH || !preg_match('/^\d+$/', $this->owner->$attribute)) {
			$this->owner->addError($attribute, $param['message']);
		}
	}

	/**
	 * проверка, что возраст в заданном диапазоне
	 * @param string $attribute дата
	 * @param array  $param
	 */
	public function checkValidAge($attribute, $param)
	{
		$iAge = $this->countYearsBetween2Dates($this->owner->$attribute, date('d.m.Y', time()));
		if ($iAge < SiteParams::C_MIN_AGE || $iAge > SiteParams::C_MAX_AGE) {
			$this->owner->addError($attribute, $param['message']);
		}
	}

	/**
	 * проверка даты выдачи паспорта на валидность
	 * @param string $attribute дата
	 * @param array  $param
	 */
	public function checkValidPassportDate($attribute, $param)
	{
		if (empty($this->owner->$param['birthDate'])) {
			$this->owner->addError($attribute, $param['messageEmptyBirthday']);

			return;
		}

		$passportDate = $this->owner->$attribute;
		$birthDate = $this->owner->$param['birthDate'];

		// дата паспорта - больше текущей даты
		if (date('Ymd', strtotime($passportDate)) > date('Ymd')) {
			$this->owner->addError($attribute, $param['message']);

			return;
		}

		// текущий возраст клиента
		$iAge = $this->countYearsBetween2Dates($birthDate, date('d.m.Y', time()));
		// возраст на момент получения паспорта
		$iAgeChangePassport = $this->countYearsBetween2Dates($birthDate, $passportDate);

		// возраст должен быть в заданном диапазоне
		if ($iAge < SiteParams::C_MIN_AGE || $iAge > SiteParams::C_MAX_AGE) {
			$this->owner->addError($attribute, $param['messageEmptyBirthday']);

			return;
		}

		// возраст смены паспорта должен быть не меньше первого заданного возраста
		if ($iAgeChangePassport < SiteParams::$aChangePassportAges[0]) {
			$this->owner->addError($attribute, $param['message']);

			return;
		}

		foreach (SiteParams::$aChangePassportAges as $iChangePassportAge) {
			if ($iAge >= $iChangePassportAge && $iAgeChangePassport < $iChangePassportAge) {
				$this->owner->addError($attribute, $param['messageExpiredDate']);

				return;
			}
		}
	}

	/**
	 * проверка учреждения, выдавшего паспорт
	 */
	public function checkValidPassportIssued($attribute, $param)
	{
		$this->owner->$attribute = trim($this->owner->$attribute);
		if (!preg_match('#^[а-яё0-9,\-.№ ]+$#ui', $this->owner->$attribute)) {
			$this->owner->addError($attribute, $param['message']);
		}
	}

	/**
	 * проверка составляющих адреса
	 */
	public function checkValidAddressRegion($attribute, $param)
	{
		$this->owner->$attribute = trim($this->owner->$attribute);
		if (!preg_match('#^[а-яё0-9,\-./() ]+$#ui', $this->owner->$attribute)) {
			$this->owner->addError($attribute, $param['message']);
		}
	}

	/**
	 * проверка номера второго документа на валидность
	 *
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidDocumentNumber($attribute, $param)
	{
		if (empty($this->owner->$param['chosenDocument'])) {
			$this->owner->addError($attribute, $param['messageEmptyDocument']);

			return;
		}

		$sNumber = $this->owner->$param['chosenDocument'];
		$sError = Dictionaries::$aDocumentsErrors[$sNumber];
		switch ($sNumber) {
			case 1:
				$sError = $this->checkValidInternationalPassport($this->owner->$attribute) ? "" : $sError;
				break;
			case 2:
				$sError = $this->checkValidDriverLicense($this->owner->$attribute) ? "" : $sError;
				break;
			case 3:
				$sError = $this->checkValidPensionCertificate($this->owner->$attribute) ? "" : $sError;
				break;
			case 4:
				$sError = $this->checkValidMilitaryCard($this->owner->$attribute) ? "" : $sError;
				break;
			case 5:
				$sError = $this->checkValidInn($this->owner->$attribute) ? "" : $sError;
				break;
			case 6:
				$sError = $this->checkValidInsuranceCertificate($this->owner->$attribute) ? "" : $sError;
				break;
			default:
				break;
		}

		if (!empty($sError)) {
			$this->owner->addError($attribute, $sError);
		}
	}


	/**
	 * Проверка на валидность номера загранпаспорта
	 * 9 цифр без пробелов и дефисов
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidInternationalPassport($sNumber)
	{
		return (preg_match('/^\d{9}$/', $sNumber) > 0);
	}

	/**
	 * Проверка на валидность номера водительского удостоверения
	 * Просто буквы или цифры
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidDriverLicense($sNumber)
	{
		return (preg_match('/^[а-яё0-9]+$/ui', $sNumber) > 0);
	}

	/**
	 * Проверка на валидность номера пенсионного удостоверения
	 * просто цифры
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidPensionCertificate($sNumber)
	{
		return (preg_match('/^\d+$/', $sNumber) > 0);
	}

	/**
	 * Проверка на валидность номера военного билета
	 * 2 русских буквы, 7 цифр
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidMilitaryCard($sNumber)
	{
		return (preg_match('/^[а-яё]{2}\d{7}$/ui', $sNumber) > 0);
	}

	/**
	 * Проверка на валидность номера ИНН
	 * 12 цифр
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidInn($sNumber)
	{
		return (preg_match('/^\d{12}$/', $sNumber) > 0);
	}

	/**
	 * Проверка на валидность номера Страховое свидетельство государственного пенсионного страхования
	 * 11 цифр
	 *
	 * @param $sNumber
	 *
	 * @return bool
	 */
	private function checkValidInsuranceCertificate($sNumber)
	{
		return (preg_match('/^\d{11}$/', $sNumber) > 0);
	}

	/**
	 * форматирование фамилий и имен
	 * @param $strName
	 */
	private function formatName(&$strName)
	{
		$strName = trim($strName);

		if (!preg_match('/[-\s]+/', $strName)) {
			$strName = $this->convertRegistrRussionWord($strName);

			return;
		}

		$contains_defis = false;
		$contains_white = false;

		// убираем обрамляющие дефис пробелы
		if (preg_match('/-/', $strName)) {
			$strName = preg_replace('/\s*-+\s*/', '-', $strName);
			$contains_defis = true;
		}

		// удаляем ненужные пробелы
		if (preg_match('/\s+/', $strName)) {
			$strName = preg_replace('/\s+/', ' ', $strName);
			$contains_white = true;
		}

		// фамилия содержит только пробел
		if (!$contains_defis) {
			$this->rebuildName($strName, ' ');

			return;
		}

		// фамилия содержит только дефис
		if (!$contains_white) {
			$this->rebuildName($strName, '-');

			return;
		}

		// если мы здесь - то слово содержит и дефис и пробел
		$this->rebuildName($strName, '-');
		$this->rebuildName($strName, ' ');
	}

	/**
	 * вспомогательная функция для нормализации фамилий и имен
	 * @param string $strName
	 * @param string $delimiter
	 */
	private function rebuildName(&$strName, $delimiter)
	{
		$aNames = explode($delimiter, $strName);

		foreach ($aNames as &$name) {
			$name = $this->convertRegistrRussionWord($name);
		}

		$strName = implode($delimiter, $aNames);
	}

	/**
	 * изменение регистра для русских букв
	 * @param $strWord
	 *
	 * @return string
	 */
	private function convertRegistrRussionWord($strWord)
	{
		$strWord = mb_strtolower($strWord, 'UTF-8');
		$strWord = mb_convert_case($strWord, MB_CASE_TITLE, "UTF-8");

		return $strWord;
	}


	/**
	 * добавляет к дате пустое значение чч:мм:сс
	 * если дата пустая, то в атрибуте будет гггг:мм:дд чч:мм:сс
	 * @param $attribute
	 *
	 * @example ContactForm::afterValidate
	 */
	public function addEmptyTime2Date($attribute)
	{

		if (strlen($this->owner->$attribute) <= 11) {
			$this->owner->$attribute = trim($this->owner->$attribute);
			if (empty($this->owner->$attribute)) {
				$this->owner->$attribute = SiteParams::EMPTY_DATE;
			}
			$this->owner->$attribute = trim($this->owner->$attribute) . ' ' . SiteParams::EMPTY_TIME;
		}

	}

	/**
	 * убирает время из формата гггг:мм:дд чч:мм:сс
	 * остается только гггг:мм:дд
	 * @param $attribute
	 */
	public function initDateFromDatetime($attribute)
	{
		$this->owner->$attribute = current(explode(' ', $this->owner->$attribute));
	}

	/**
	 * Считает, сколько полных лет прошло с даты $sDate
	 *
	 * @param string $sDate1
	 * @param string $sDate2
	 *
	 * @internal param string $sDate
	 *
	 * @return int
	 */
	private function countYearsBetween2Dates($sDate1, $sDate2)
	{
		return (int)((date('Ymd', strtotime($sDate2)) - date('Ymd', strtotime($sDate1))) / 10000);
	}


	/**
	 * Превращает дни и часы в секунды
	 *
	 * @param $attribute
	 */
	public function initTimeFromDaysHours($attribute)
	{
		$sDaysAttribute = $attribute . '_days';
		$sHoursAttribute = $attribute . '_hours';

		$sDays = $this->owner->$sDaysAttribute;
		$sHours = $this->owner->$sHoursAttribute;

		$iSeconds = ($sDays * 60 * 60 * 24) + ($sHours * 60 * 60);

		$this->owner->$attribute = $iSeconds;
	}


	/**
	 * превращает timestamp в дни и часы,
	 * сохраняя их в спец-полях *_days и *_hours
	 *
	 * @param $attribute
	 */
	public function initDaysHoursFromTime($attribute)
	{

		$iTime = $this->getOwner()->$attribute;

		$iDays = floor($iTime / 60 / 60 / 24);
		$iHours = $iTime - $iDays * 60 * 60 * 24;
		$iHours = $iHours / 60 / 60;

		$sAttrHours = $attribute . '_hours';
		$sAttrDays = $attribute . '_days';

		$this->getOwner()->$sAttrHours = (int)$iHours;
		$this->getOwner()->$sAttrDays = (int)$iDays;

	}

	/**
	 * Проверяет на то, что первая дата не позже второй
	 *
	 * @param $dateAttribute1
	 * @param $dateAttribute2
	 */
	public function checkConflict2Dates($dateAttribute1, $dateAttribute2)
	{
		$iDate1 = $this->owner->$dateAttribute1;
		$iDate2 = $this->owner->$dateAttribute2;

		$iDate1 = strtotime($iDate1);
		$iDate2 = strtotime($iDate2);

		$sDate1Label = $this->owner->getAttributeLabel($dateAttribute1);
		$sDate2Label = $this->owner->getAttributeLabel($dateAttribute2);

		if (!$iDate1) {
			$iDate1 = 0;
		}
		if (!$iDate2) {
			$iDate2 = 0;
		}


		if ($iDate1 > 0 && $iDate2 == 0) {
			return;
		} else {
			if ($iDate1 > $iDate2) {
				$this->owner->addError($dateAttribute1, $sDate1Label . ' не может быть позже или в тот же день с ' . $sDate2Label);
			}
		}
	}

	/**
	 * @param $attribute
	 * @param $param
	 */
	public function checkFriendsOnJobPhone($attribute, $param)
	{

		$sPhone = $this->owner->$param['phone'];
		$sJobPhone = $this->owner->$param['job_phone'];

		if ($sJobPhone == $sPhone && empty($this->owner->$attribute)) {
			$this->owner->addError($attribute, $param['message']);
		}

		if ($attribute == "friends_phone" && !empty($this->owner->$attribute) && $sJobPhone == $this->owner->$attribute) {
			$this->owner->addError($attribute, $param['message2']);
		}

		return;

	}

	/**
	 * @param $attribute
	 * @param $param
	 */
	public function checkAddressRes($attribute, $param)
	{

		$sRegAsRes = $this->owner->$param['reg_as_res'];

		if (!$sRegAsRes) {
			if (empty($this->owner->$attribute)) {
				$this->owner->addError($attribute, $param['message']);
			}
			if ($attribute === 'address_res_region' && !in_array($this->owner->$attribute, array_keys(Dictionaries::getRegions()))) {
				$this->owner->addError($attribute, $param['message2']);
			}
			if ($attribute !== 'address_res_region') {
				$this->checkValidAddressRegion($attribute, array('message' => $param['message2']));
			}
		}

		return;

	}

	/**
	 * @param $attribute
	 * @param $param
	 */
	public function checkOldPassport($attribute, $param)
	{
		$sPassportNotChanged = $this->owner->$param['passport_not_changed'];


		if (!$sPassportNotChanged) {
			if (empty($this->owner->$attribute)) {
				$this->owner->addError($attribute, $param['message']);
			}
		}

		return;

	}

	/**
	 * проверка, что содержит только русские буквы и знаки препинания
	 * @param string $attribute
	 * @param array  $param
	 */
	public function checkValidRus($attribute, $param)
	{
		if ($this->owner->$attribute) {
			if (!preg_match('#^[а-яё0-9,\-./() ]+$#ui', $this->owner->$attribute)) {
				$this->owner->addError($attribute, $param['message']);
			} else {
				$this->formatName($this->owner->$attribute);
			}
		}
	}

	/**
	 * Проверка required дополнительных полей в случае если паспорт утерян или украден
	 *
	 * @param $attribute
	 * @param $param
	 */
	public function checkPassportLostStolen($attribute, $param)
	{
		//2=>'Утеря или кража',
		$bPassportLostStolen = ($this->owner->$param['passport_change_reason']==2);
		if($bPassportLostStolen&&empty($this->owner->$attribute)){
			$this->owner->addError($attribute, $param['message']);
		}

	}

	/**
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidCardPan($attribute, $param)
	{
		$iCardType = $this->owner->$param['iCardType'];
		if (isset(Dictionaries::$aCardTypesRegexp[$iCardType]) && !preg_match(Dictionaries::$aCardTypesRegexp[$iCardType], $this->owner->$attribute)) {
			$this->owner->addError($attribute, $param['message']);
		}
	}

}
