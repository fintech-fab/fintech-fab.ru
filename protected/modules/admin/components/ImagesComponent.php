<?php
/**
 * Created by JetBrains PhpStorm.
 * User: popov
 * Date: 26.08.13
 * Time: 11:09
 * To change this template use File | Settings | File Templates.
 */
class ImagesComponent
{

	public static function ImagesList()
	{
		$sImagesDir = Yii::app()->getBasePath() . '/../public/uploads/images';
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sImagesDir)) as $sFileName) {

			if (!is_dir($sFileName)) {
				$sReplace = str_replace('/uploads/images', '', $sImagesDir);

				$sFileName = str_replace($sReplace, Yii::app()
					->getBaseUrl(), $sFileName);
				$sThumbName = str_replace('uploads/images', 'uploads/thumbnails', $sFileName);
				$aItems[] = array('thumb' => $sThumbName, 'image' => $sFileName);
			}
		}
		if (!isset($aItems)) {
			$aItems = array();
		}

		return $aItems;
	}

	public static function ImagesAdminItems()
	{
		$sImagesDir = Yii::app()->getBasePath() . '/../public/uploads/images';
		$iIndex = 0;
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sImagesDir)) as $sFileName) {
			if (!is_dir($sFileName)) {
				$sReplace = str_replace('/uploads/images', '', $sImagesDir);

				$sFileName = str_replace($sReplace, Yii::app()
					->getBaseUrl(), $sFileName);
				$sThumbName = str_replace('uploads/images', 'uploads/thumbnails', $sFileName);
				$aItems[] = array('id' => $iIndex, 'thumb' => $sThumbName, 'image' => $sFileName);
				$iIndex += 1;
			}
		}
		if (!isset($aItems)) {
			$aItems = array();
		}


		foreach ($aItems as &$sItem) {

			$sSql = "SELECT COUNT(*) FROM `tbl_pages` WHERE `page_content` REGEXP '" . $sItem['image'] . "'";

			$iCount = Pages::model()->countBySql($sSql);

			$sItem['count_pages'] = $iCount;
		}
		foreach ($aItems as &$sItem) {

			$sSql = "SELECT COUNT(*) FROM `tbl_bottom_tabs` WHERE `tab_content` REGEXP '" . $sItem['image'] . "'";

			$iCount = Tabs::model()->countBySql($sSql);

			$sItem['count_tabs'] = $iCount;
		}
		foreach ($aItems as &$sItem) {

			$sSql = "SELECT COUNT(*) FROM `tbl_footer_links` WHERE `link_content` REGEXP '" . $sItem['image'] . "'";

			$iCount = FooterLinks::model()->countBySql($sSql);

			$sItem['count_footer_links'] = $iCount;
		}

		return $aItems;
	}

	public static function ImageDelete($sImage)
	{
		$sPath = Yii::app()->getBasePath() . '/../public/uploads/images/';
		@unlink($sPath . $sImage);
	}

	/**
	 * Код для миграции, создание уменьшенных картинок для проекта, имеющего только полноразмерные картинки
	 */
	public static function ThumbnailsGenerate()
	{
		$sImagesDir = Yii::app()->getBasePath() . '/../public/uploads/images';
		$sThumbsDir = Yii::app()->getBasePath() . '/../public/uploads/thumbnails';

		if (!file_exists($sImagesDir)) {
			@mkdir($sImagesDir);
		}

		if (!file_exists($sThumbsDir)) {
			@mkdir($sThumbsDir);
		}

		$sCommand = "cp -f -R " . $sImagesDir . "/* " . $sThumbsDir;

		shell_exec($sCommand);

		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sImagesDir)) as $sFileName) {

			if (!is_dir($sFileName)) {

				$sThumbName = str_replace('uploads/images', 'uploads/thumbnails', $sFileName);

				@copy($sFileName, $sThumbName);


				if ($imgSize = getimagesize($sThumbName)) {
					if ($imgSize[0] > 100) {
						$iFactor = $imgSize[1] / $imgSize[0];
						$image = new Image($sThumbName);
						$image->resize(100, (int)(100 * $iFactor));
						$image->save();
					}
				}
			}
		}
	}

}