<?php

class FormController extends Controller
{
	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oForm
		 * @var array $aPost
		 * @var string $sView
		 */

		if(Yii::app()->clientForm->ajaxValidation())
		{
			Yii::app()->end();
		}

		$oForm=Yii::app()->clientForm->getFormModel();

		if($aPost=Yii::app()->clientForm->getPostData())
		{
			$oForm->attributes=$aPost;
			//var_dump($oForm);
			//var_dump($oForm->validate());
			if($oForm->validate())
			{
				//Yii::app()->end();
				//Yii::app()->clientForm->formDataProcess($oForm);
				Yii::app()->clientForm->nextStep();
			}
		}

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));

		//$oForm=new ClientSendForm();
		//$this->render('clientsend',array('oClientCreateForm'=>$oForm));

		//$this->render('clientpersonaldata',array('oClientCreateForm'=>$oForm));
	}

	public function actionStart()
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
