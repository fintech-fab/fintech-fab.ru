<?php

class FilesController extends Controller
{
	public $layout = '/layouts/row2';

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
				'actions' => array('index', 'imageUpload', 'imagesList', 'imagesAdmin', 'delete'),
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
		Yii::import('admin.components.ImagesComponent');

		$aImages = ImagesComponent::imagesList();
		echo CJSON::encode($aImages);
	}

	public function actionImagesAdmin()
	{
		Yii::import('admin.components.ImagesComponent');

		$aItems = ImagesComponent::imagesAdminItems();

		$sort = new CSort;
		$sort->defaultOrder = 'id ASC';
		$sort->attributes = array('id', 'count_pages', 'count_tabs', 'count_footer_links');
		$itemsProvider = new CArrayDataProvider($aItems, array('sort' => $sort));
		$this->render('admin', compact('itemsProvider'));
	}

	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('admin/files/imagesAdmin'));
	}


	public function actionDelete($image)
	{
		Yii::import('admin.components.ImagesComponent');

		ImagesComponent::imageDelete($image);

		if (!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('files/imagesAdmin'));
		}
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