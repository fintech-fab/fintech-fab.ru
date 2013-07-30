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

		if(Yii::app()->FormComponent->ajaxValidation())
		{
			Yii::app()->end();
		}

		$oForm=Yii::app()->FormComponent->getFormModel();

		if($aPost=Yii::app()->FormComponent->getPostData())
		{
			$oForm->attributes=$aPost;
			if($oForm->validate())
			{
				Yii::app()->FormComponent->formDataProcess($oForm);
			}
		}

		$sView=Yii::app()->FormComponent->getView();
		$this->render($sView,array('model'=>$oForm));
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
