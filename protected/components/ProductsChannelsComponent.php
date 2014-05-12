<?php

/**
 * Компонент ProductsChannelsComponent
 *
 * Отвечает за вывод на сайт списка доступных комбинаций продуктов/каналов, полученных из AdminKreddyApi
 * TODO возможно стоит вернуть все функции в AdminKreddyApiComponent
 */
class ProductsChannelsComponent
{
	public function init()
	{

	}

	/**
	 * @return array
	 */
	public static function getProducts()
	{
		// получаем массив доступных продуктов
		$aProducts = Yii::app()->adminKreddyApi->getProducts();

		// возвращаем форматированный массив
		return self::formatProductsList($aProducts);
	}

	/**
	 * @return array
	 */
	public static function getKreddyLineProductsCosts()
	{
		$aProducts = Yii::app()->adminKreddyApi->getProducts();

		$aProductsList = array();
		$aProductsListByAmount = array();
		foreach ($aProducts as $iKey => $aProduct) {
			$iAmount = $aProduct['loan_amount'];
			$aProductsListByAmount[$iAmount][$iKey] = $aProduct;
		}

		foreach ($aProductsListByAmount as $iAmount => $aProductsInAmount) {
			$sProductsIds = implode('_', array_keys($aProductsInAmount));
			$aProductsList[$sProductsIds] = $iAmount;
		}

		return $aProductsList;
	}

	/**
	 * @param $sProductsIds
	 * @param $iType
	 *
	 * @return bool
	 */
	public static function getProductBySelectedType($sProductsIds, $iType)
	{
		$aProductsIds = explode('_', $sProductsIds);
		if (!empty($aProductsIds)) {
			$aProducts = Yii::app()->adminKreddyApi->getProducts();

			foreach ($aProductsIds as $iProductId) {
				if (isset($aProducts[$iProductId]['type']) && $aProducts[$iProductId]['type'] == $iType) {
					return $iProductId;
				}
			}
		}

		return false;
	}

	/**
	 * Формирует массив со списком продуктов
	 *
	 * @param $aProducts
	 *
	 * @return array
	 */
	public static function formatProductsList($aProducts)
	{
		$aProductsList = array();
		if (is_array($aProducts)) {

			foreach ($aProducts as $key => $aProduct) {
				$iLoanLifetime = (int)($aProduct['loan_lifetime'] / 3600 / 24);
				$iSubscriptionLifetime = (int)($aProduct['subscription_lifetime'] / 3600 / 24);
				$iCardPrice = 0;
				if (isset($aProduct['channels']) && is_array($aProduct['channels'])) {
					foreach ($aProduct['channels'] as $aChannel) {
						if (!empty($aChannel['additional_cost'])) {
							$iCardPrice = $aChannel['additional_cost'];
						}
					}
				}

				$aProductsList[$key] = "<span data-price='" . $aProduct['subscription_cost']
					. "' data-final-price='" . $aProduct['loan_amount']
					. "' data-card='" . $iCardPrice . "' data-price-count='"
					. $iSubscriptionLifetime . "&nbsp;дней"
					. "' data-count='" . $aProduct['loan_count'] . '&nbsp;' . self::getLoanCountLabel($aProduct['loan_count'])
					. "' data-int-count='" . $aProduct['loan_count']
					. "' data-time='" . $iLoanLifetime . "'>"
					. $aProduct['name']
					. "</span>";
			}
		} else {
			$aProductsList = array('0' => '<span data-price="0" data-final-price="0" data-price-count="0 дней" data-count="0 займов" data-int-count="0" data-time="0">Произошла ошибка! Попробуйте перезагрузить страницу через минуту.</span>',);
		}

		return $aProductsList;
	}

	/**
	 * @param $iLoanCount
	 *
	 * @return string
	 */
	public static function getLoanCountLabel($iLoanCount)
	{
		$iLoanCount = $iLoanCount % 10;
		switch ($iLoanCount) {
			case 1:
				$sLabel = 'займ';
				break;
			case 2:
			case 3:
			case 4:
				$sLabel = 'займа';
				break;
			default:
				$sLabel = 'займов';
				break;
		}

		return $sLabel;
	}

	/**
	 * @param      $sName
	 *
	 * @internal param bool $bNoOperatorNames
	 *
	 * @return string
	 */
	public static function formatChannelName($sName)
	{
		if (preg_match("/Кредди/", $sName)) {
			return 'на карту Кредди';
		} elseif (preg_match("/мобил/", $sName)) {
			return 'на мобильный телефон (МТС, Билайн, Мегафон, Теле2)';
		} elseif (preg_match("/карт/", $sName)) {
			return 'на карту Mastercard, Maestro, Visa';
		}

		return false;
	}

	/**
	 * @param $sName
	 *
	 * @return bool|string
	 */
	public static function formatChannelNameNoOperators($sName)
	{
		if (preg_match("/Кредди/", $sName)) {
			return 'на карту Кредди';
		} elseif (preg_match("/мобил/", $sName)) {
			return 'на мобильный телефон';
		}

		return SiteParams::mb_lcfirst($sName);
	}

	/**
	 * @param $sName
	 *
	 * @return bool|string
	 */
	public static function formatChannelNameForStatus($sName)
	{
		if (preg_match("/Кредди/", $sName)) {
			return 'на карту Кредди';
		} elseif (preg_match("/мобил/", $sName)) {
			return 'на мобильный телефон';
		} elseif (preg_match("/карту/", $sName)) {
			return 'на банковскую карту';
		}

		return SiteParams::mb_lcfirst($sName);
	}

	/**
	 * Получение списка каналов, с объединением мобильных каналов в один
	 *
	 */

	public function getChannels()
	{
		$aChannels = Yii::app()->adminKreddyApi->getProductsChannels();
		$aChannelsList = array();
		$sMobileChannels = '';
		$sCardChannels = '';
		foreach ($aChannels as $iKey => $sChannelName) {
			if (strpos($sChannelName, 'Кредди')) {
				continue;
			}
			if (strpos($sChannelName, 'карт')) {
				if (!empty($sCardChannels)) {
					$sCardChannels .= '_';
				}
				$sCardChannels .= $iKey;
				$sCardChannelName = $sChannelName;
			} elseif (strpos($sChannelName, 'мобильный')) {
				if (!empty($sMobileChannels)) {
					$sMobileChannels .= '_';
				}
				$sMobileChannels .= $iKey;
				$sMobileChannelName = $sChannelName;
			}
		}
		if (!empty($sCardChannels) && !empty($sCardChannelName)) {
			$aChannelsList[$sCardChannels] = '<span data-card="0">' . SiteParams::mb_ucfirst(self::formatChannelName($sCardChannelName)) . '</span>';
		}
		if (!empty($sMobileChannels) && !empty($sMobileChannelName)) {
			$aChannelsList[$sMobileChannels] = '<span data-card="0">' . SiteParams::mb_ucfirst(self::formatChannelName($sMobileChannelName)) . '</span>';
		}


		return $aChannelsList;
	}

	/**
	 * Получение списка каналов для "КРЕДДИтной линии"
	 *
	 * @return array
	 */
	public function getChannelsKreddyLine()
	{
		$aChannels = Yii::app()->adminKreddyApi->getProductsChannels();
		$aChannelsList = array();
		$sMobileChannels = '';
		$sCardChannels = '';
		foreach ($aChannels as $iKey => $sChannelName) {
			if (strpos($sChannelName, 'Кредди')) {
				continue;
			}
			if (strpos($sChannelName, 'карт')) {
				if (!empty($sCardChannels)) {
					$sCardChannels .= '_';
				}
				$sCardChannels .= $iKey;
				$sCardChannelName = $sChannelName;
			} elseif (strpos($sChannelName, 'мобильный')) {
				if (!empty($sMobileChannels)) {
					$sMobileChannels .= '_';
				}
				$sMobileChannels .= $iKey;
				$sMobileChannelName = $sChannelName;
			}
		}
		if (!empty($sCardChannels) && !empty($sCardChannelName)) {
			$aChannelsList[$sCardChannels] = 'Банковская карта<br/><span style="font-size: 10pt;">MasterCard, VISA</span>';
		}
		if (!empty($sMobileChannels) && !empty($sMobileChannelName)) {
			$aChannelsList[$sMobileChannels] = 'Мобильный телефон<br/><span style="font-size: 10pt;">МТС, Билайн, Мегафон, ТЕЛЕ2</span>';
		}


		return $aChannelsList;
	}

	/**
	 * @return array
	 */
	public function getChannelsForButtons()
	{
		$aChannels = Yii::app()->adminKreddyApi->getProductsChannels();
		$aChannelsList = array();
		$sMobileChannels = '';
		foreach ($aChannels as $iKey => $sChannelName) {
			if (strpos($sChannelName, 'мобильный')) {
				if (!empty($sMobileChannels)) {
					$sMobileChannels .= '_';
				}
				$sMobileChannels .= $iKey;
				$sMobileChannelName = $sChannelName;
			} elseif (!strpos($sChannelName, 'Кредди')) {
				$aChannelsList[$iKey] = $sChannelName;
			}
		}
		if (!empty($sMobileChannels) && !empty($sMobileChannelName)) {
			$aChannelsList[$sMobileChannels] = SiteParams::mb_ucfirst(self::formatChannelName($sMobileChannelName));
		}

		/**
		 * Получение каналов для текущего клиента, если он залогинен в ЛК
		 */
		//если текущий модуль account
		if (Yii::app()->controller->getModule() && Yii::app()->controller->getModule()->getName() === 'account') {
			//получаем каналы, доступные клиенту
			$aClientChannels = Yii::app()->adminKreddyApi->getClientChannels();
			if (!empty($aClientChannels)) {
				$aClientChannelsList = array();
				//перебираем все каналы
				foreach ($aChannelsList as $iKey => $sChannel) {
					//проверяем, что данный канал доступен пользователю
					if (in_array($iKey, $aClientChannels)) {
						//TODO тут сейчас берется 1 канал, найденный в каналах пользователя и имеющийся в списке каналов в формате 1_2_3
						//TODO надо сделать чтобы можно было и больше каналов можно было получить в массиве
						$iKey = Yii::app()->adminKreddyApi->getClientSelectedChannelByIdString($iKey);
						$aClientChannelsList[$iKey] = SiteParams::mb_ucfirst(self::formatChannelNameNoOperators($sChannel)); //убираем названия операторов

					}
				}
				$aChannelsList = $aClientChannelsList;
			}
		}

		return $aChannelsList;
	}
}
