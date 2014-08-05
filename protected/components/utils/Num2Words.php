<?php

/**
 * Class Num2Words
 */
class Num2Words
{

	const C_MALE = 1; //Мужской род
	const C_FEMALE = 2; //Женский род
	const C_NEUTER = 3; //Средний род

	const C_MAX_INT = 999999999999999; //Максимальное число
	const C_MAX_PRECISION = 99999; //Максимально знаков после запятой

	const C_RUB = 'RUB'; //Рубли
	const C_KOP = 'KOP'; //Копейки
	const C_INT = 'INT'; //Целых (сто одно целое)

	const C_PRECISION10 = 'PRECISION10'; //Десятые
	const C_PRECISION100 = 'PRECISION100'; //Cотые
	const C_PRECISION1000 = 'PRECISION1000'; //Тысячные
	const C_PRECISION10000 = 'PRECISION10000'; //Десятитысячные
	const C_PRECISION100000 = 'PRECISION100000'; //Стотысячные

	/**
	 * Зависимость единиц измерения до запятой и после
	 *
	 * @var array
	 */
	private static $aUnitDependents = array(
		self::C_RUB => self::C_KOP,
		self::C_INT => array(
			1 => self::C_PRECISION10,
			2 => self::C_PRECISION100,
			3 => self::C_PRECISION1000,
			4 => self::C_PRECISION10000,
			5 => self::C_PRECISION100000,
		)
	);

	/**
	 * Денежные единицы
	 *
	 * @var array
	 */
	private static $aCurrency = array(self::C_RUB, self::C_KOP);

	private static $sNull = 'ноль';

	private static $aTen = array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять');

	/**
	 * Расширение переменной $sTen с учетом рода
	 *
	 * @var array
	 */
	private static $aTenGenus = array(
		self::C_MALE   => array(),
		self::C_FEMALE => array(1 => 'одна', 2 => 'две'),
		self::C_NEUTER => array(1 => 'одно'),
	);

	private static $aTen10_19 = array(
		'десять',
		'одиннадцать',
		'двенадцать',
		'тринадцать',
		'четырнадцать',
		'пятнадцать',
		'шестнадцать',
		'семнадцать',
		'восемнадцать',
		'девятнадцать'
	);

	private static $aTens = array(
		2 => 'двадцать',
		3 => 'тридцать',
		4 => 'сорок',
		5 => 'пятьдесят',
		6 => 'шестьдесят',
		7 => 'семьдесят',
		8 => 'восемьдесят',
		9 => 'девяносто'
	);

	private static $aHundreds = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');

	/**
	 * Окончания
	 *
	 * @var array
	 */
	private static $aOrdinalUnits = array(
		1 => array('тысяча', 'тысячи', 'тысяч', 'genus' => self::C_FEMALE),
		2 => array('миллион', 'миллиона', 'миллионов', 'genus' => self::C_MALE),
		3 => array('миллиард', 'милиарда', 'миллиардов', 'genus' => self::C_MALE),
		4 => array('триллион', 'триллиона', 'триллионов', 'genus' => self::C_MALE),
	);

	private static $aUnits = array(
		'INT'             => array('целое', 'целых', 'целых', 'genus' => self::C_NEUTER),
		'RUB'             => array('рубль', 'рубля', 'рублей', 'genus' => self::C_MALE),
		'KOP'             => array('копейка', 'копейки', 'копеек', 'genus' => self::C_FEMALE),
		'PRECISION10'     => array('десятая', 'десятых', 'десятых', 'genus' => self::C_FEMALE),
		'PRECISION100'    => array('сотая', 'сотых', 'сотых', 'genus' => self::C_FEMALE),
		'PRECISION1000'   => array('тысячная', 'тысячных', 'тысячных', 'genus' => self::C_FEMALE),
		'PRECISION10000'  => array('десятитысячная', 'детсятитысячных', 'детсятитысячных', 'genus' => self::C_FEMALE),
		'PRECISION100000' => array('стотысячная', 'стотысячных', 'стотысячных', 'genus' => self::C_FEMALE),
	);

	/**
	 * Преобразовать число в строку прописью
	 *
	 * @param $fNumber
	 * @param $cIntUnit
	 *
	 * @throws Num2WordsException
	 * @return string
	 */
	public static function doConvert($fNumber, $cIntUnit)
	{

		//Если единица измерения - денежная, то округляем до 2х знаков после запятой
		if (self::isCurrency($cIntUnit)) {
			$fNumber = number_format(round($fNumber, 2), 2, '.', '');
		}

		//Если число без точки
		if (strpos($fNumber, '.') === false) {
			$fNumber = sprintf('%.1f', $fNumber);
		}

		//Разбиваем число на значение до запятой и после
		list($sInt, $sPrecision) = explode('.', $fNumber);

		//Дополняем нулями
		$sInt = sprintf('%015d', $sInt);

		//Проверяем корректность введенного числа
		if (abs($sInt) > self::C_MAX_INT || $sPrecision > self::C_MAX_PRECISION) {
			throw new Num2WordsException('Не верно задано число');
		}


		//Получаем в число до запятой в виде строки
		$sTextInt = self::getNumber2Words($sInt, $cIntUnit);

		$sTextPrecision = '';
		if ($sPrecision != 0 || in_array($cIntUnit, self::$aCurrency)) {

			//Определяем единицу измерения части после запятой
			$cPrecisionUnit = self::getPrecisionUnitByMain($cIntUnit, $sPrecision);

			//Получаем в число после запятой в виде строки
			$sTextPrecision = self::getNumber2Words($sPrecision, $cPrecisionUnit);

		}

		return $sTextInt . ' ' . $sTextPrecision;
	}

	/**
	 * Преобразует часть числа (после запятой или до запятой) в строку прописью
	 *
	 * @param        $sNumber
	 * @param string $cUnit
	 *
	 * @return string
	 */
	private static function getNumber2Words($sNumber, $cUnit)
	{
		$aOut = array();

		//Проверяем знак
		if ($sNumber < 0) {
			$aOut[] = 'минус';
		}

		if ($sNumber != 0) {

			//Разбиваем число на части по 3 знака
			$aSplit3Chars = str_split($sNumber, 3);

			//Количество частей
			$iSplitCount = count($aSplit3Chars);

			foreach ($aSplit3Chars as $iKey => $sVal) {

				if ($sVal == 0) {
					continue;
				}

				//Если меньше 100 (меньше 3х знаков), то дополняем нулями
				$sVal = sprintf('%3d', $sVal);

				//Получаем ключ единицы измерения для текущей части
				$iUnitKey = $iSplitCount - $iKey - 1;

				//Получаем массив единины измерения
				$aCurrentUnits = self::getUnits($cUnit)[$iUnitKey];

				//Получаем род единицы измерения
				$iCurrentGenus = $aCurrentUnits['genus'];

				//Разбиваем часть на числа
				list($i1, $i2, $i3) = array_map('intval', str_split($sVal, 1));

				//Тысячи (сто - девятьсот)
				$aOut[] = self::$aHundreds[$i1];

				if ($i2 > 1) { // x2x - x9x
					//Десятки - от "двадцать" до "девяносто"
					$aOut[] = self::$aTens[$i2];
					// от "один" до "девять"
					$aOut[] = self::getTenByGenus($iCurrentGenus)[$i3]; // 1-9
				} elseif ($i2 > 0) { // x10 - x19
					// от "десять" до "девятнадцать"
					$aOut[] = self::$aTen10_19[$i3];
				} else { // < x10
					// от "один" до "девять"
					$aOut[] = self::getTenByGenus($iCurrentGenus)[$i3];
				}

				//Берем единицы измерения с учетом рода последнего символа
				$aOut[] = $aCurrentUnits[self::getMorphIndex($sVal)];

			}

			// Если кратно 1000
			if ($sNumber % 1000 == 0) {
				//Берем единицы измерения с учетом рода последнего символа
				$aCurrentUnits = self::getUnits($cUnit)[0];
				$aOut[] = $aCurrentUnits[2];
			}

		} else {
			$aOut[] = self::$sNull;
			$aOut[] = self::getUnits($cUnit)[0][2]; //Рублей | Целых и т.д.
		}

		return trim(implode(' ', $aOut));
	}

	/**
	 * Получить массив чисел от 1 до 10 с учетом рода
	 *
	 * @param int $cGenus
	 *
	 * @return array
	 */
	private static function getTenByGenus($cGenus = self::C_MALE)
	{
		return self::$aTenGenus[$cGenus] + self::$aTen;
	}

	/**
	 * Получить массив единиц измерения для целочисленной части с учетом единиц измерения
	 *
	 * @param int $cIntUnits
	 *
	 * @return array
	 */
	private static function getUnits($cIntUnits)
	{
		$aIntUnits = self::$aOrdinalUnits;
		$aIntUnits[0] = self::$aUnits[$cIntUnits];

		return $aIntUnits;
	}

	/**
	 * Склоняем словоформу
	 *
	 * @param $iInt
	 *
	 * @return int
	 */
	private static function getMorphIndex($iInt)
	{
		$iInt = abs(intval($iInt)) % 100;
		if ($iInt > 10 && $iInt < 20) {
			return 2;
		}
		$iInt = $iInt % 10;
		if ($iInt > 1 && $iInt < 5) {
			return 1;
		}
		if ($iInt == 1) {
			return 0;
		}

		return 2;
	}

	/**
	 * Ищем единицу измерения для части после запятой
	 *
	 * @param $cUnit
	 * @param $sPrecisionVal
	 *
	 * @return int
	 */
	private static function getPrecisionUnitByMain($cUnit, $sPrecisionVal)
	{

		if ($cUnit == self::C_INT) {
			$iCount = strlen($sPrecisionVal);

			return self::$aUnitDependents[$cUnit][$iCount];
		}

		return self::$aUnitDependents[$cUnit];
	}

	/**
	 * Является ли единица денежной
	 *
	 * @param $cUnit
	 *
	 * @return bool
	 */
	private static function isCurrency($cUnit)
	{
		return in_array($cUnit, self::$aCurrency);
	}
}

/**
 * Class Num2WordsException
 */
class Num2WordsException extends Exception
{

}

