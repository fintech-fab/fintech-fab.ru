<?php


class BankiRuFilter extends CFilter
{

	public function preFilter($aFilterChain)
	{
		$this->detectLink();

		return true;
	}

	/**
	 * Определяет что ссылка по которой пришел пользователь содержит информацию о Banki_ru.
	 * В случае успеха устанавливаем двухмесячную Cookie для клиента
	 */
	private function detectLink()
	{
		$sPkCampaign = Yii::app()->request->getQuery('pk_campaign');

		if (!$sPkCampaign) {
			return;
		}

		if (strtolower($sPkCampaign) !== 'banki_ru') {
			return;
		}

		$sWid = Yii::app()->request->getQuery('wid');
		if ($sWid) {
			$oCookie = new CHttpCookie('banki_ru', $sWid);
			$oCookie->expire = SiteParams::getTime() + SiteParams::CTIME_TWO_MONTH;
			$oCookie->httpOnly = true;

			Yii::app()->request->cookies->add('banki_ru', $oCookie);
		}

	}

} 