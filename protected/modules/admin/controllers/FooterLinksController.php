<?php

class FooterLinksController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','imageUpload','view','sort'),
				'users'=>array(Yii::app()->params['adminName']),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FooterLinks;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FooterLinks']))
		{
			$model->attributes=$_POST['FooterLinks'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->link_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FooterLinks']))
		{
			$model->attributes=$_POST['FooterLinks'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->link_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('FooterLinks');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FooterLinks('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FooterLinks']))
			$model->attributes=$_GET['FooterLinks'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionSort()
	{
		if( Yii::app()->request->isAjaxRequest )
		{
			if( isset( $_POST[ 'items' ] ) && is_array( $_POST[ 'items' ] ) )
			{
				foreach( $_POST[ 'items' ] as $key => $val )
				{
					FooterLinks::model()->updateByPk( $val, array (
						'link_order' => ( $key + 1 )
					) );
				}
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FooterLinks the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FooterLinks::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FooterLinks $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='footer-links-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
