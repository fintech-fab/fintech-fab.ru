<?php


class LinkprofitFilter extends CFilter
{

	public function preFilter($aFilterChain)
	{
		$this->detectLink();

		return true;
	}

	/**
	 * Определяет что ссылка по которой пришел пользователь содержит информацию о Linkprofit.
	 * В случае успеха устанавливаем двухмесячную Cookie для клиента
	 */
	private function detectLink()
	{
		$sUtmSource = Yii::app()->request->getQuery('utm_source');

		if (!$sUtmSource){
			return;
		}

		if (strtolower($sUtmSource) !== 'linkprofit'){
			return;
		}

		$sWid = Yii::app()->request->getQuery('wid');
		if ($sWid) {
			$oCookie = new CHttpCookie('linkprofit', $sWid);
			$oCookie->expire = SiteParams::getTime() + SiteParams::CTIME_TWO_MONTH;
			$oCookie->httpOnly = true;

			Yii::app()->request->cookies->add('linkprofit', $oCookie);
		}

	}

} 