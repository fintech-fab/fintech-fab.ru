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

	const C_ERR_GENERAL = 'Ошибка: обратитесь в горячую линию.';
	const C_ERR_SMS_SENT = 'Ошибка: SMS уже отправлена';
	const C_ERR_SMS_TRIES = 'Вы превысили число допустимых попыток ввода кода. Обратитесь в горячую линию.';
	const C_ERR_SMS_WRONG = 'Неверный код подтверждения!';
	const C_ERR_TRIES_LEFT = 'Осталось попыток: ';

	const C_SMS_SUCCESS = 'SMS успешно отправлена!';

	const C_SEX_FEMALE = 1;
	const C_SEX_MALE = 2;

	/**
	 * названия для пола
	 * @var array
	 */
	public static $aSexes = array(
		self::C_SEX_FEMALE => 'Женский',
		self::C_SEX_MALE => 'Мужской'
	);

	const C_MARITAL_MARRIED		= 1;
	const C_MARITAL_NOT_MARRIED	= 2;

	/**
	 * названия для семейного положения
	 * @var array
	 */
	public static $aMaritalStatuses = array(
		self::C_MARITAL_MARRIED => 'женат/замужем',
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
		self::C_NO => 'Нет',
	);

	/**
	 * @var array
	 */
	public static $aCitizenship = array(
		1 => 'Россия',
	);

	public static $aRegions = array();

	/**
	 * названия для  образования
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

	public static $aJobTimes = array(
		1 =>'Менее года',
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
	 * @var array
	 */
	public static $aLiabilities = array(
		1 => 'нет',
		2 => 1000,
		3 => 2000,
		4 => 3000,
		5 => 4000,
		6 => 5000,
		7 => 6000,
		8 => 7000,
		9 => 8000,
		10 => 9000,
		11 => 10000,
		12 => 'более 10000',
	);

	/**
	 * дни выдачи зарплаты/аванса
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
	 * варианты второго документа
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
	 * названия дней недели для вывода в блоке "Выбранные условия"
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
	 * @var array
	 */
	public static $aMonths = array(
		1 => "января",
		2 => "февраля",
		3 => "марта",
		4 => "апреля",
		5 => "мая",
		6 => "июня",
		7 => "июля",
		8 => "августа",
		9 => "сентября",
		10 => "октября",
		11 => "ноября",
		12 => "декабря",
	);

	/**
	 * Выбор суммы займа
	 * @var array
	 */
	public static $aProducts = array(
		"1"=>"<span data-price='350' data-final-price='3000' data-price-count='30 дней' data-count='2 займа' data-time='7'>3000 рублей на неделю</span>",
		"2"=>"<span data-price='1500' data-final-price='6000' data-price-count='60 дней' data-count='4 займа' data-time='7'>6000 рублей на неделю</span>",
		"3"=>"<span data-price='1500' data-final-price='10000' data-price-count='60 дней' data-count='2 займа' data-time='14'>10000 рублей на 2 недели</span>",
	);

	/**
	 * Цена за подписку
	 * @var array
	 */
	public static $aDataPrices = array(
		"1"=>"350",
		"2"=>"1500",
		"3"=>"1500",
	);

	/**
	 * Сумма займа (просто цифры)
	 * @var array
	 */
	public static $aDataFinalPrices = array(
		"1"=>"3000",
		"2"=>"6000",
		"3"=>"10000",
	);

	/**
	 * Длительность подписки
	 * @var array
	 */
	public static $aDataPriceCounts = array(
		"1"=>"30 дней",
		"2"=>"60 дней",
		"3"=>"60 дней",
	);

	/**
	 * Кол-во возможных займов
	 * @var array
	 */
	public static $aDataCounts = array(
		"1"=>"2 займа",
		"2"=>"4 займа",
		"3"=>"2 займа",
	);

	/**
	 * Срок возврата займа
	 * @var array
	 */
	public static $aDataTimes = array(
		"1"=>"7",
		"2"=>"7",
		"3"=>"14",
	);

	/**
	 * Выбор способа получения займа в зависимости от выбранной на предыдущем шаге суммы
	 * @param int $chosen_sum_index
	 * @var array
	 */
	public static function aWays($chosen_sum_index)
	{
		switch ($chosen_sum_index)
		{
			case 1:
			{
				return array(
					"1"=>"На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
					"2"=>"На сотовый телефон",
				);
				break;
			}
			case 2:
			{
				return array(
					"1"=>"На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
			}
			case 3:
			{
				return array(
					"1"=>"На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
			}
			default:
			{
				return array(
					"1"=>"На карту <a data-toggle='modal' href='#fl-contacts'>Kreddy MasterCard</a>",
				);
				break;
			}
		}
	}

	/**
	 * варианты секретного вопроса
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
	 * @param $id
	 * @return string
	 */
	public static function getSecretQuestionName($id)
	{
		return empty( self::$aSecretQuestions[$id] )
			? ''
			: self::$aSecretQuestions[$id]
			;
	}

	/**
	 * название второго документа
	 *
	 * @param $id
	 * @return string
	 */
	public static function getDocumentName($id)
	{
		return empty( self::$aDocuments[$id] )
			? ''
			: self::$aDocuments[$id]
		;
	}


	/**
	 * Название пола по id
	 * @param $id
	 * @return string
	 */
	public static function getSexName($id)
	{
		return empty( self::$aSexes[$id] )
			? ''
			: self::$aSexes[$id]
		;
	}

	/**
	 * гражданство по id
	 * @param $id
	 * @return string
	 */
	public static function getCitizenshipName($id)
	{
		return empty( self::$aCitizenship[$id] )
			? ''
			: self::$aCitizenship[$id]
		;
	}

	/**
	 * Название образования по id
	 * @param $id
	 * @return string
	 */
	public static function getEducationName($id)
	{
		return empty( self::$aEducations[$id] )
			? ''
			: self::$aEducations[$id]
		;
	}

	/**
	 * список регионов
	 * @return array
	 */
	public static function getRegions()
	{
		if( empty( self::$aRegions ) ){
			self::$aRegions = CHtml::listData(
				Yii::app()->db->createCommand()
					->select('id,name')
					->from('regions')
					->where('`flag_active`=1')
					->order('sort ASC')
					->queryAll()
				,
				'id', 'name'
			);
		}

		return self::$aRegions;
	}

	public static function getRegionName($id)
	{
		return empty( self::$aRegions[$id] )
			? ''
			: self::$aRegions[$id]
		;
	}

	public static function getMaritalName($id)
	{
		return empty( self::$aMaritalStatuses[$id] )
			? ''
			: self::$aMaritalStatuses[$id]
		;
	}

	public static function getLiabilitiesName($id)
	{
		return empty( self::$aLiabilities[$id] )
			? ''
			: self::$aLiabilities[$id]
		;
	}

	public static function getYesNo($id)
	{
		if($id === null){
			return 'Не указано';
		}
		return self::$aYesNo[$id];
	}
}
