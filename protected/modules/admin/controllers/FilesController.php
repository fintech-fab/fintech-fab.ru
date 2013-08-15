<?php

class FilesController extends Controller
{
	public function actions()
	{
		return array(
			'imageUpload' => array(
				'class'           => 'ext.RedactorUploadAction',
				'directory'       => 'uploads/images',
				'thumb_directory' => 'uploads/thumbnails',
				'validator'       => array(
					'mimeTypes' => array('image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg'),
				)
			),
			'fileUpload'  => array(
				'class'     => 'ext.RedactorUploadAction',
				'directory' => 'uploads/files',
				'validator' => array(
					'types' => 'txt, pdf, doc, docx',
				)
			),
		);
	}

	/**
	 *
	 */

	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('admin/pages'));
	}

	/**
	 * @return array
	 */

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array(''),
				'users'   => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(''),
				'users'   => array('@'),
			),
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('imageUpload', 'imagesList', 'imagesAdmin'),
				'users'   => array(Yii::app()->params['adminName']),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionImagesList()
	{
		$a = Yii::app()->getBasePath() . '/../public/uploads/images';
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($a)) as $sFileName) {

			if (!is_dir($sFileName)) {
				$sFileName = str_replace('/var/www/ru.dev.kreddy/protected/../public', Yii::app()
					->getBaseUrl(), $sFileName);
				$sThumbName = str_replace('uploads/images', 'uploads/thumbnails', $sFileName);
				$array_items[] = array('thumb' => $sThumbName, 'image' => $sFileName);
			}
		}
		if (!isset($array_items)) {
			$array_items = array();
		}
		echo CJSON::encode($array_items);
	}

	public function actionImagesAdmin()
	{
		$a = Yii::app()->getBasePath() . '/../public/uploads/images';
		$iIndex = 0;
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($a)) as $sFileName) {
			if (!is_dir($sFileName)) {
				$sFileName = str_replace('/var/www/ru.dev.kreddy/protected/../public', Yii::app()
					->getBaseUrl(), $sFileName);
				$sThumbName = str_replace('uploads/images', 'uploads/thumbnails', $sFileName);
				$array_items[] = array('id'=>$iIndex,'thumb' => $sThumbName, 'image' => $sFileName);
				$iIndex+=1;
			}
		}
		if (!isset($array_items)) {
			$array_items = array();
		}


		foreach ($array_items as &$item) {

			$sSql = "SELECT COUNT(*) FROM `tbl_pages` WHERE `page_content` REGEXP '" . $item['image'] . "'";

			$iCount = Pages::model()->countBySql($sSql);

			$item['count_pages'] = $iCount;
		}
		foreach ($array_items as &$item) {

			$sSql = "SELECT COUNT(*) FROM `tbl_bottom_tabs` WHERE `tab_content` REGEXP '" . $item['image'] . "'";

			$iCount = Tabs::model()->countBySql($sSql);

			$item['count_tabs'] = $iCount;
		}
		foreach ($array_items as &$item) {

			$sSql = "SELECT COUNT(*) FROM `tbl_footer_links` WHERE `link_content` REGEXP '" . $item['image'] . "'";

			$iCount = FooterLinks::model()->countBySql($sSql);

			$item['count_footer_links'] = $iCount;
		}
		$itemsProvider = new CArrayDataProvider($array_items);
		$this->render('admin', compact('itemsProvider'));
	}

	private function directoryToArray($directory, $recursive)
	{
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory . "/" . $file)) {
						if ($recursive) {
							$array_items = array_merge($array_items, $this->directoryToArray($directory . "/" . $file, $recursive));
						}
						$file = $directory . "/" . $file;
						//$array_items[] = preg_replace("/\/\//si", "/", $file);
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}

		return $array_items;
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}