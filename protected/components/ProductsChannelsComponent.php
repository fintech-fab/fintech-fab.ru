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
				$aProductsList[$key] = "<span data-price='" . $aProduct['subscription_cost']
					. "' data-final-price='" . $aProduct['amount'] . "' data-price-count='"
					. $iSubscriptionLifetime . "&nbsp;дней"
					. "' data-count='" . $aProduct['loan_count'] . "&nbsp;займа"
					. "' data-int-count='" . $aProduct['loan_count']
					. "' data-time='" . $iLoanLifetime . "'>"
					. $aProduct['name']
					. "</span>";
			}
		} else {
			$aProductsList = array("0" => "<span data-price='0' data-final-price='0' data-price-count='0 дней' data-count='0 займов' data-int-count='0' data-time='0'>Произошла ошибка! Попробуйте перезагрузить страницу через минуту.</span>",);
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
}
