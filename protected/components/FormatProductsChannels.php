<?php
/**
 * Class FormatProductsChannels
 *
 * Отвечает за вывод на сайт списка доступных комбинаций продуктов/каналов, полученных из AdminKreddyApi
 */

class FormatProductsChannels
{
	/**
	 * @return array
	 */
	public static function getProducts()
	{
		// получаем массив доступных продуктов и каналов
		$aProducts = Yii::app()->adminKreddyApi->getClientProductsAndChannelsList4Site();

		// возвращаем форматированный массив
		return self::formatProductsList4Site($aProducts);
	}

	/**
	 * Формирует массив "продукт_канал" => "описание продукта с учётом канала"
	 *
	 * @param $aProducts
	 *
	 * @return array
	 */
	public static function formatProductsList4Site($aProducts)
	{
		$aProductsList = array();
		if (is_array($aProducts)) {
			foreach ($aProducts as $key => $aProduct) {
				$iLoanLifetime = (int)($aProduct['loan_lifetime'] / 3600 / 24);
				$iSubscriptionLifetime = (int)($aProduct['subscription_lifetime'] / 3600 / 24);
				$aProductsList[$key] = "<span data-price='" . $aProduct['subscription_cost']
					. "' data-final-price='" . $aProduct['amount'] . "' data-price-count='"
					. $iSubscriptionLifetime
					. "' data-count='" . $aProduct['loan_count']
					. "' data-time='" . $iLoanLifetime . "'>"
					. $aProduct['amount'] . " рублей на "
					. ($iLoanLifetime == 7 ? 'неделю' : ($iLoanLifetime == 14 ? '2 недели' : $iLoanLifetime . ' дней'))
					. " " . self::formatChannelName($aProduct['channel_name']) . "</span>";
			}
		} else {
			$aProductsList = array("0" => "<span data-price='350' data-final-price='3000' data-price-count='30 дней' data-count='2 займа' data-time='7'>Произошла ошибка!</span>",);
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
	}
}
