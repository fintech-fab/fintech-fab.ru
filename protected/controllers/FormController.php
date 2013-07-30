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
	/*
		if(Yii::app()->ClientForm->ajaxValidation())
		{
			Yii::app()->end();
		}

		$oForm=Yii::app()->ClientForm->getFormModel();

		if($aPost=Yii::app()->ClientForm->getPostData())
		{
			$oForm->attributes=$aPost;
			if($oForm->validate())
			{
				Yii::app()->ClientForm->formDataProcess($oForm);
			}
		}

	*/
		//$sView=Yii::app()->ClientForm->getView();
		//$this->render($sView,array('model'=>$oForm));


		$oForm=new ClientPersonalDataForm();
		$this->render('clientpersonaldata',array('oClientCreateForm'=>$oForm));
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
