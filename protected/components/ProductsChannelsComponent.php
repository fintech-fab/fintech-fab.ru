<?php
/**
 * Компонент ProductsChannelsComponent
 *
 * Отвечает за вывод на сайт списка доступных комбинаций продуктов/каналов, полученных из AdminKreddyApi
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
					. "' data-final-price='" . $aProduct['amount']
					. "' data-card='" . $iCardPrice . "' data-price-count='"
					. $iSubscriptionLifetime . "&nbsp;дней"
					. "' data-count='" . $aProduct['loan_count'] . "&nbsp;займа"
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
	 * @param $sName
	 *
	 * @return string
	 */
	public static function formatChannelName($sName)
	{
		if (preg_match("/карт/", $sName)) {
			return 'на карту Кредди';
		} elseif (preg_match("/мобил/", $sName)) {
			return 'на мобильный (МТС, Билайн, Мегафон)';
		}

		return false;
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
		foreach ($aChannels as $iKey => $sChannelName) {
			if (strpos($sChannelName, 'мобильный')) {
				if (!empty($sMobileChannels)) {
					$sMobileChannels .= '_';
				}
				$sMobileChannels .= $iKey;
				$sMobileChannelName = $sChannelName;
			} elseif (!strpos($sChannelName, 'Кредди')) {
				$aChannelsList[$iKey] = '<span data-card="1">' . $sChannelName . '</span>';
			}
		}
		if (!empty($sMobileChannels) && !empty($sMobileChannelName)) {
			$aChannelsList[$sMobileChannels] = '<span data-card="0">' . SiteParams::mb_ucfirst(self::formatChannelName($sMobileChannelName)) . '</span>';
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
				echo '<pre>' . "";
				CVarDumper::dump($aClientChannels);
				echo '</pre>';
				echo '<pre>' . "";
				CVarDumper::dump($aChannelsList);
				echo '</pre>';

				$aClientChannelsList = array();
				//перебираем все каналы
				foreach ($aChannelsList as $iKey => $sChannel) {
					//проверяем, что данный канал доступен пользователю
					if (in_array($iKey, $aClientChannels)) {
						$aClientChannelsList[$iKey] = $sChannel; //формируем массив каналов, доступных пользователю
					}
				}
				$aChannelsList = $aClientChannelsList;
			}
		}

		return $aChannelsList;
	}
}
