<?php

class FilesController extends Controller
{
	public function actions()
	{
		return array(
			'imageUpload'=>array(
				'class' => 'ext.RedactorUploadAction',
				'directory'=>'uploads/images',
				'validator'=>array(
					'mimeTypes' => array('image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg'),
				)
			),
			'fileUpload'=>array(
				'class' => 'ext.RedactorUploadAction',
				'directory'=>'uploads/files',
				'validator'=>array(
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
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('imagesList'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('imageUpload'),
				'users'=>array(Yii::app()->params['adminName']),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionImagesList()
	{
		$a = Yii::app()->getBasePath().'/../public/uploads/images';
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($a)) as $filename)
		{

			if(!is_dir($filename)){
				$filename = str_replace('/var/www/ru.dev.kreddy/protected/../public', Yii::app()->getBaseUrl(), $filename);
				$array_items[]	= array('thumb'=>$filename,'image' =>$filename, 'title' => $filename,'folder'=>'Folder' );
			}
		}
		if(!isset($array_items)){
			$array_items = array();
		}

		echo CJSON::encode($array_items);


	}

	private function directoryToArray($directory, $recursive) {
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, $this->directoryToArray($directory. "/" . $file, $recursive));
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