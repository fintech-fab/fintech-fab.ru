<?php

/**
 * Class FaqQuestionController
 */
class FaqQuestionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '/layouts/row2';

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

	/**
	 * @return array
	 */
	public function actions()
	{
		return array(
			'imageUpload' => array(
				'class'     => 'ext.RedactorUploadAction',
				'directory' => 'uploads/images',
				'validator' => array(
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
			'toggle'      => array(
				'class'     => 'bootstrap.actions.TbToggleAction',
				'modelName' => 'FaqQuestion',
			),
			'sortable'    => array(
				'class'     => 'bootstrap.actions.TbSortableAction',
				'modelName' => 'FaqQuestion'
			),
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
				'actions' => array('admin', 'index', 'delete', 'create', 'update', 'view', 'sortable', 'toggle'),
				'users'   => array(Yii::app()->params['adminName']),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 *
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$oFaqGroup = FaqGroup::model()->findAll();

		if (empty($oFaqGroup)) {
			$this->render('no_groups');
			Yii::app()->end();
		}

		$model = new FaqQuestion;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

		if (isset($_POST['FaqQuestion'])) {
			$model->attributes = $_POST['FaqQuestion'];
			if ($model->save()) {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

		if (isset($_POST['FaqQuestion'])) {
			$model->attributes = $_POST['FaqQuestion'];
			if ($model->save()) {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}


	/**
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
		} else {
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('FaqQuestion');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new FaqQuestion('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['FaqQuestion'])) {
			$model->attributes = $_GET['FaqQuestion'];
		}

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * @param $id
	 *
	 * @return CActiveRecord
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = FaqQuestion::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		return $model;
	}

	/**
	 * @param $model
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'faq-question-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
