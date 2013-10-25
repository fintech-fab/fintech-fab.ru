<?php

/**
 * Class SiteUtils
 */
class SiteUtils
{
	/**
	 * @param $sHtml
	 *
	 * @return mixed
	 */
	public static function purifyHtml($sHtml)
	{
		$oPurifier = new CHtmlPurifier;
		$sHtml = $oPurifier->purify($sHtml);
		return $sHtml;
	}

}