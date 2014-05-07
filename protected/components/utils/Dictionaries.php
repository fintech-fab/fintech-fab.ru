<?php

/**
 * Created by JetBrains PhpStorm.
 * User: e.zamorskaya
 * Date: 22.04.13
 * Time: 15:00
 * To change this template use File | Settings | File Templates.
 */
class Dictionaries
{

	const C_ERR_GENERAL = 'Ошибка: обратитесь в контактный центр.';
	const C_ERR_SMS_SENT = 'Ошибка: SMS уже отправлено';
	const C_ERR_SMS_CANT_SEND = "Ошибка при отправке SMS";
	const C_ERR_SMS_TRIES = 'Вы превысили число допустимых попыток ввода кода. Обратитесь в контактный центр.';
	const C_ERR_SMS_PASS_TRIES = 'Вы превысили число допустимых попыток ввода пароля. Обратитесь в контактный центр.';
	const C_ERR_SMS_WRONG = 'Неверный код подтверждения!';
	const C_ERR_TRIES_LEFT = 'Осталось попыток: ';

	const C_SMS_SUCCESS_NUM = 'SMS с кодом успешно отправлено на номер';
	const C_SMS_SUCCESS = 'SMS с кодом успешно отправлено';
	const C_SMS_PASS_SUCCESS = 'SMS с паролем успешно отправлено';
	const C_SMS_RESET_PASSWORD_SUCCESS = "SMS с паролем отправлено на указанный Вами номер";

	const C_INFO_MOSCOWTIME = "Указано московское время";

	const C_FAQ_SUBJECT_SENT = "Вопрос с Kreddy.ru";
	const C_FAQ_SUCCESS = "Спасибо за Ваш вопрос! Специалист ответит Вам в ближайшее время.";

	const C_SEX_FEMALE = 1;
	const C_SEX_MALE = 2;
	public static $aPayTypes = array(
		3 => 'Оплатить сейчас<br/><span style="font-size: 10pt;">абонентскяя плата - 900 руб/мес</span>',
		4 => 'Оплатить потом<br/><span style="font-size: 10pt;">абонентскяя плата - 1000 руб/мес</span>',
	);

	/**
	 * названия для пола
	 *
	 * @var array
	 */
	public static $aSexes = array(
		self::C_SEX_FEMALE => 'Женский',
		self::C_SEX_MALE   => 'Мужской'
	);

	const C_MARITAL_MARRIED = 1;
	const C_MARITAL_NOT_MARRIED = 2;

	/**
	 * названия для семейного положения
	 *
	 * @var array
	 */
	public static $aMaritalStatuses = array(
		self::C_MARITAL_MARRIED     => 'женат/замужем',
		self::C_MARITAL_NOT_MARRIED => 'холост/не замужем'
	);

	/**
	 * константы на да/нет
	 * для случаев, когда возможно значение "не определен"
	 */
	const C_YES = 1;
	const C_NO = 2;

	public static $aYesNo = array(
		self::C_YES => 'Да',
		self::C_NO  => 'Нет',
	);

	/**
	 * @var array
	 */
	public static $aCitizenship = array(
		1 => 'Россия',
	);

	public static $aRegions = array();

	/**
	 * Подсказки для полей формы
	 *
	 * @var array
	 */
	public static $aHintsFormFields = array(
		'last_name'  => 'Пожалуйста, введите свою фамилию. Все документы будут оформлены на указанное здесь имя и фамилию.',
		'first_name' => 'Пожалуйста, введите свое имя.',
		'third_name' => 'Пожалуйста, введите свое отчество.',
		'birthday'   => 'Пожалуйста, укажите свою дату рождения.',
		'phone'      => 'Для Вашей успешной идентификации введите Ваш личный номер мобильного телефона.',
		'email'      => 'Введите Ваш адрес электронной почты.',
		/*''           => 'Пожалуйста, отметьте Ваш пол.',

		''           => 'Пожалуйста, укажите серию и номер Вашего паспорта.',
		''           => 'Пожалуйста, укажите дату выдачи паспорта.',
		''           => 'Пожалуйста, введите код подразделения, выдавшего Ваш паспорт – указан на второй странице паспорта.',
		''           => 'Пожалуйста, укажите государственный орган, выдавший паспорт.',
		''           => 'Пожалуйста, выберите из списка название Вашего второго документа.',
		''           => 'Пожалуйста, введите номер документа. Обратите внимание, что выбранный Вами документ содержит _ символов. Пожалуйста, внимательно введите номер.',

		''           => 'Пожалуйста, укажите регион постоянной регистрации.',
		''           => 'Пожалуйста, введите название Вашего населенного пункта в соответствии с постоянной регистрацией.',
		''           => 'Пожалуйста, введите данные постоянной регистрации: название улицы, номер дома, номер корпуса/строения, номер квартиры.',
		''           => 'Пожалуйста, введите фамилию, имя, отчество любого родственника или знакомого, данный контакт необходим в случае, если мы не сможем с Вами связаться.',
		''           => 'Пожалуйста, введите номер телефона Вашего родственника или знакомого, данный контакт необходим в случае, если мы не сможем с Вами связаться.',
		''           => 'Пожалуйста, введите фамилию, имя, отчество.',
		''           => 'Пожалуйста, введите номер телефона.',
		''           => 'Пожалуйста, укажите регион Вашего фактического проживания.',
		''           => 'Пожалуйста, введите название населенного пункта, в котором Вы фактически проживаете.',
		''           => 'Пожалуйста, введите адрес Вашего фактического проживания : название улицы, номер дома, номер корпуса/строения, номер квартиры.',


		''           => 'Пожалуйста, укажите название организации, в которой работаете. ',
		''           => 'Пожалуйста, укажите свою должность.',
		''           => 'Введите номер рабочего телефона.',
		''           => 'Введите свой трудовой стаж на текущем месте работы.',


		''           => 'Пожалуйста, укажите Ваш среднемесячный доход.',
		''           => 'Пожалуйста, укажите сумму Ваших среднемесячных расходов.',

		''           => 'Пожалуйста, придумайте и введите в поле свой цифровой код. Количество символов – от 4 до 8. ',
		''           => 'Пожалуйста, выберите секретный вопрос.',
		''           => 'Пожалуйста, введите ответ на секретный вопрос.',
		''           => 'Создайте и введите свой пароль. Обращаем внимание, что пароль набирается только в латинской раскладке и должен содержать не менее 6 символов, в том числе не менее одной цифры, одной заглавной и одной строчной буквы.',
		''           => 'Пожалуйста, повторно введите пароль для входа в личный кабинет.',
*/
	);

	/**
	 * Темы вопросов FAQ
	 *
	 * @var array
	 */
	public static $aSubjectsQuestions = array(
		1 => 'Подключение сервиса',
		2 => 'Правила пользования сервисом',
		3 => 'Возврат займа',
		4 => 'Личный кабинет',
		5 => 'Пакеты займов',
		6 => 'Предложения по улучшению сервиса',
		7 => 'Претензии',
		8 => 'Другие вопросы',
	);

	/**
	 * названия для  образования
	 *
	 * @var array
	 */
	public static $aEducations = array(
		1 => 'Незаконченное среднее',
		2 => 'Среднее',
		3 => 'Среднее-специальное',
		4 => 'Незаконченное высшее',
		5 => 'Высшее',
		6 => 'Аспирантура',
		7 => 'Докторантура',
	);

	/**
	 * Статус Клиента
	 *
	 * @var array
	 */
	public static $aStatuses = array(
		1 => 'Штатный сотрудник',
		2 => 'ИП',
		3 => 'Студент',
		4 => 'Пенсионер',
		5 => 'Безработный',
		6 => 'Домохозяйка',
	);

	public static $aJobTimes = array(
		1 => 'Менее года',
		2 => '1 год',
		3 => '2 года',
		4 => '3 года',
		5 => '4 года',
		6 => '5 лет',
		7 => 'Более 5 лет',
	);


	public static $aMonthlyMoney = array(
		1 => 10000,
		2 => 15000,
		3 => 20000,
		4 => 25000,
		5 => 30000,
		6 => 35000,
		7 => 40000,
		8 => 'более 40000',
	);

	public static $aOverMoney = array(
		1 => 'нет',
		2 => 'Менее 5000 руб',
		3 => 'От 5000 до 10000 руб',
		4 => 'От 10000 до 15000 руб',
		5 => 'Более 15000',
	);

	/**
	 * дополнительный расход
	 *
	 * @var array
	 */
	public static $aLiabilities = array(
		1  => 'нет',
		2  => 1000,
		3  => 2000,
		4  => 3000,
		5  => 4000,
		6  => 5000,
		7  => 6000,
		8  => 7000,
		9  => 8000,
		10 => 9000,
		11 => 10000,
		12 => 'более 10000',
	);

	/**
	 * дни выдачи зарплаты/аванса
	 *
	 * @var array
	 */
	public static $aMoneyDays = array(
		1 => '1-5 число месяца',
		2 => '6-10 число месяца',
		3 => '11-15 число месяца',
		4 => '16-20 число месяца',
		5 => '21-25 число месяца',
		6 => '26-30 число месяца',
	);

	/**
	 * Цели займа
	 *
	 * @var array
	 */
	public static $aLoanPurposes = array(
		1 => 'Здоровье и красота',
		2 => 'Развлечения (кафе, кино и т.п.)',
		3 => 'Подарки друзьям и близким',
		4 => 'Продукты питания',
		5 => 'Непродовольственные товары',
		6 => 'Расходы на детей',
		7 => 'Пополнение счета мобильного телефона',
		8 => 'Оплата других кредитов/займов',
		9 => 'Другое',
	);

	/**
	 * варианты второго документа
	 *
	 * @var array
	 */
	public static $aDocuments = array(
		1 => 'Заграничный паспорт',
		2 => 'Водительское удостоверение',
		3 => 'Пенсионное удостоверение',
		4 => 'Военный билет',
		5 => 'Свидетельство ИНН',
		6 => 'Страховое свидетельство государственного пенсионного страхования',
	);

	/**
	 * правила заполнения второго документа
	 *
	 * @var array
	 */
	public static $aDocumentsRegexps = array(
		1 => '/^\d{9}$/', // серия (2 символа) и номер (7 символов)
		2 => '/^[а-яё0-9]{4}\d{6}$/ui', // серия (4 символа - могут быть и цифры и буквы) и номер (6 цифр). Например, 12ЖД 123456. (Итого - 10 символов)
		3 => '/^\d+$/', //
		4 => '/^[а-яё]{2}\d{7}$/ui', // серия (2 символа) номер (7 символов). Серия из букв, номер из цифр. Например, ИМ 1234567. (Итого 9 символов)
		5 => '/^\d{12}$/', // 12 цифр - например 123456789123
		6 => '/^\d{11}$/', //  номер только из цифр, например, 123-456-789-01. (Итого 11 символов)
	);

	/**
	 * Сообщения об ошибках для второго документа
	 *
	 * @var array
	 */
	public static $aDocumentsPopovers = array(
		0 => 'Пожалуйста, выберите из списка название Вашего второго документа',
		1 => 'Введите серию и номер заграничного паспорта (всего 9 цифр). Пример: 123456789',
		2 => 'Введите номер водительского удостоверения, используя только цифры и русские буквы',
		3 => 'Введите номер пенсионного удостоверения',
		4 => 'Введите номер военного билета, используя только цифры и русские буквы. Пример: АБ1234567',
		5 => 'Введите 12 цифр номера ИНН. Пример: 123456789012',
		6 => 'Введите 11 цифр номера страхового свидетельства. Пример: 12345678901',
	);

	/**
	 * названия дней недели для вывода в блоке "Выбранные условия"
	 *
	 * @var array
	 */
	public static $aDays = array(
		0 => "воскресенья",
		1 => "понедельника",
		2 => "вторника",
		3 => "среды",
		4 => "четверга",
		5 => "пятницы",
		6 => "субботы",
	);

	/**
	 * месяца года для вывода в блоке "Выбранные условия"
	 *
	 * @var array
	 */
	public static $aMonths = array(
		1  => 'января',
		2  => 'февраля',
		3  => 'марта',
		4  => 'апреля',
		5  => 'мая',
		6  => 'июня',
		7  => 'июля',
		8  => 'августа',
		9  => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря',
	);

	public static $aMonthsDigital = array(
		'01' => '01',
		'02' => '02',
		'03' => '03',
		'04' => '04',
		'05' => '05',
		'06' => '06',
		'07' => '07',
		'08' => '08',
		'09' => '09',
		'10' => '10',
		'11' => '11',
		'12' => '12',
	);

	/**
	 * @return array
	 */


	public static $aCardTypes = array(
		'1' => 'Mastercard',
		'2' => 'Maestro',
		'3' => 'Visa',
	);

	public static $aCardTypesRegexp = array(
		'1' => '/^5{1}[0-5]{1}/', //номера начинаются с 50-55
		'2' => '/^6{1}[0-9]{1}/', //номера начинаются с 60-69
		'3' => '/^4{1}[0-9]{1}/', // номера начинаются с 40-49
	);

	/**
	 * Возвращает массив из $iQuantity следующуих за текущим годом лет
	 *
	 * @param int  $iQuantity
	 * @param bool $bAdd2000 прибавлять ли 2000
	 *
	 * @return array
	 */
	public static function getYears($iQuantity = 10, $bAdd2000 = false)
	{
		$curYear = (int)date('y');
		$endYear = $curYear + $iQuantity;
		$aYears = array();
		for ($i = $curYear; $i <= $endYear; $i++) {
			$aYears[$i] = $bAdd2000 ? ($i + 2000) : $i;
		}

		return $aYears;
	}

	/**
	 * Выбор суммы займа
	 *
	 * @var array
	 */
	public static $aProducts = array(
		"1" => "<span data-price='350' data-final-price='3000' data-price-count='30 дней' data-count='2 займа' data-time='7'>3000 рублей на неделю</span>",
		"2" => "<span data-price='1500' data-final-price='6000' data-price-count='60 дней' data-count='4 займа' data-time='7'>6000 рублей на неделю</span>",
		"3" => "<span data-price='1500' data-final-price='10000' data-price-count='60 дней' data-count='2 займа' data-time='14'>10000 рублей на 2 недели</span>",
	);

	/**
	 * Цена за подписку
	 *
	 * @var array
	 */
	public static $aDataPrices = array(
		"1" => "350",
		"2" => "1500",
		"3" => "1500",
	);

	/**
	 * Сумма займа (просто цифры)
	 *
	 * @var array
	 */
	public static $aDataFinalPrices = array(
		"1" => "3000",
		"2" => "6000",
		"3" => "10000",
	);

	/**
	 * Длительность подписки
	 *
	 * @var array
	 */
	public static $aDataPriceCounts = array(
		"1" => "30&nbsp;дней",
		"2" => "60&nbsp;дней",
		"3" => "60&nbsp;дней",
	);

	/**
	 * Кол-во возможных займов
	 *
	 * @var array
	 */
	public static $aDataCounts = array(
		"1" => "2&nbsp;займа",
		"2" => "4&nbsp;займа",
		"3" => "2&nbsp;займа",
	);

	/**
	 * Срок возврата займа
	 *
	 * @var array
	 */
	public static $aDataTimes = array(
		"1" => "7",
		"2" => "7",
		"3" => "14",
	);

	public static $aChangePassportReasons = array(
		1 => 'Замена в связи с окончанием срока действия (достижение 20, 45 лет)',
		2 => 'Утеря или кража',
		3 => 'Замена в связи с изменением ФИО, сведений о дате и (или) месте рождения, пола или внешности',
		4 => 'Замена в связи с непригодностью паспорта для дальнейшего использования (износ, повреждения и др.)',
		5 => 'Замена в связи с обнаружением неточности или ошибочности произведенных в паспорте записей'
	);

	/**
	 * Выбор способа получения займа в зависимости от выбранной на предыдущем шаге суммы
	 *
	 * @param int $chosen_sum_index
	 *
	 * @var array
	 * @return array
	 */

	public static function aChannels($chosen_sum_index)
	{
		switch ($chosen_sum_index) {
			case 1:
			{
				return array(
					"1" => "На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
					"2" => "На сотовый телефон (МТС, Билайн, Мегафон)",
				);
				break;
			}
			case 2:
			{
				return array(
					"1" => "На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
			}
			case 3:
			{
				return array(
					"1" => "На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
			}
			default:
				{
				return array(
					"1" => "На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
				}
		}
	}

	/**
	 * варианты секретного вопроса
	 *
	 * @var array
	 */
	public static $aSecretQuestions = array(
		1 => "ФИО классного руководителя",
		2 => "Марка вашей первой машины",
		3 => "Имя Фамилия лучшего друга детства",
		4 => "Ваш первый питомец",
		5 => "Марка первого мобильного телефона",
		6 => "Ваш герой детства",
	);

	/**
	 * выбранный секретный вопрос
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function getSecretQuestionName($id)
	{
		return empty(self::$aSecretQuestions[$id])
			? ''
			: self::$aSecretQuestions[$id];
	}

	/**
	 * название второго документа
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function getDocumentName($id)
	{
		return empty(self::$aDocuments[$id])
			? ''
			: self::$aDocuments[$id];
	}


	/**
	 * Название пола по id
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function getSexName($id)
	{
		return empty(self::$aSexes[$id])
			? ''
			: self::$aSexes[$id];
	}

	/**
	 * гражданство по id
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function getCitizenshipName($id)
	{
		return empty(self::$aCitizenship[$id])
			? ''
			: self::$aCitizenship[$id];
	}

	/**
	 * Название образования по id
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function getEducationName($id)
	{
		return empty(self::$aEducations[$id])
			? ''
			: self::$aEducations[$id];
	}

	/**
	 * список регионов
	 *
	 * @return array
	 */
	public static function getRegions()
	{
		if (empty(self::$aRegions)) {
			self::$aRegions = CHtml::listData(
				Yii::app()->db->createCommand()
					->select('id,name')
					->from('tbl_regions')
					->where('`flag_active`=1')
					->order('sort ASC')
					->queryAll()
				,
				'id', 'name'
			);
		}

		return self::$aRegions;
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function getRegionName($id)
	{
		return empty(self::$aRegions[$id])
			? ''
			: self::$aRegions[$id];
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function getMaritalName($id)
	{
		return empty(self::$aMaritalStatuses[$id])
			? ''
			: self::$aMaritalStatuses[$id];
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function getLiabilitiesName($id)
	{
		return empty(self::$aLiabilities[$id])
			? ''
			: self::$aLiabilities[$id];
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */

	public static function getYesNo($id)
	{
		if ($id === null) {
			return 'Не указано';
		}

		return self::$aYesNo[$id];
	}

	/**
	 * @return bool
	 */
	public static function isMoscowRegion()
	{
		if (isset(Yii::app()->session['isMoscowRegion']) && Yii::app()->session['isMoscowRegion']) {
			return true;
		} else {
			$sIp = Yii::app()->request->getUserHostAddress();
			$sRegion = ids_ipGeoBase::getRegionByIP($sIp);
			if ($sRegion) {
				if ($sRegion = 'Москва') {
					Yii::app()->session['isMoscowRegion'] = true;

					return true;
				} else {
					return false;
				}
			} else {
				$oIpLite = new ip2location_lite;
				$locations = $oIpLite->getCity($sIp);

				if (!empty($locations) && is_array($locations) && !empty($locations['regionName'])) {
					if ($locations['regionName'] === 'MOSCOW CITY') {
						Yii::app()->session['isMoscowRegion'] = true;

						return true;
					} else {
						return false;
					}
				}
			}

			return true; //если не удалось узнать регион, считаем что московский
		}
	}

	/**
	 * @param $iLoans
	 *
	 * @return string
	 */
	public static function formatLoanLabel($iLoans)
	{
		switch ($iLoans) {
			case 1:
				$sFormattedLabel = $iLoans . ' займ';
				break;
			case 2:
			case 3:
			case 4:
				$sFormattedLabel = $iLoans . ' займа';
				break;
			default:
				$sFormattedLabel = $iLoans . ' займов';
				break;
		}
		return $sFormattedLabel;
	}

	/**
	 * @param $str
	 *
	 * @return string
	 */
	public static function translitIt($str)
	{
		$tr = array(
			"А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
			"Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
			"Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
			"О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
			"У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
			"Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "",
			"Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
			"в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
			"з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
			"м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
			"с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
			"ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
			"ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
			" " => "_", "." => "", "/" => "_"
		);

		return strtr($str, $tr);
	}

	/**
	 * @param $sUrl
	 *
	 * @return mixed|string
	 */
	public static function createTransliteratedProductName($sUrl)
	{
		if (preg_match('/[^A-Za-z0-9]/', $sUrl)) {
			$sUrl = self::translitIt($sUrl);
			$sUrl = preg_replace('/[^A-Za-z0-9]/', '', $sUrl);
		}
		$sUrl = str_replace('kredditnayaliniya', 'kreddyline', $sUrl);

		$sUrl = str_replace('kreddi', 'kreddy', $sUrl);
		$sUrl = str_replace('dney', 'days', $sUrl);

		return $sUrl;
	}


}
