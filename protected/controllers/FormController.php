<?php

class FormController extends Controller
{
	public $showTopPageWidget=true;

	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oForm
		 * @var array $aPost
		 * @var string $sView
		 */

		$clientData=new ClientData();

		$oForm=Yii::app()->clientForm->getFormModel();

		if(Yii::app()->clientForm->ajaxValidation())
		{
			echo IkTbActiveForm::validate($oForm);
			Yii::app()->clientForm->saveAjaxData($clientData,$oForm);
			Yii::app()->end();
		}

		if($aPost=Yii::app()->clientForm->getPostData())
		{
			$oForm->attributes=$aPost;
			if($oForm->validate())
			{
				//Yii::app()->clientForm->formDataProcess($oForm);
				Yii::app()->clientForm->nextStep();
				$oForm=Yii::app()->clientForm->getFormModel();
			}
		}

		$sView=Yii::app()->clientForm->getView();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStart()//функция для тестирования, сбрасывает сессию
	{
		Yii::app()->session['current_step']='';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
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
