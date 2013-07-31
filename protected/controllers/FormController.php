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

		$clientData=new ClientData(); //объект CActiveRecord для записи в БД

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oForm=Yii::app()->clientForm->getFormModel();
		if(isset( Yii::app()->session['client_id']))
		{
			$oForm->client_id=Yii::app()->session['client_id'];
		}

		if(Yii::app()->clientForm->ajaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			echo IkTbActiveForm::validate($oForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($clientData,$oForm); //сохраняем полученные при ajax-запросе данные
			Yii::app()->end();
		}

		if($aPost=Yii::app()->clientForm->getPostData())//проверяем, был ли POST запрос
		{
			$oForm->attributes=$aPost; //передаем запрос в форму
			if($oForm->validate())
			{
				//Yii::app()->clientForm->formDataProcess($oForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oForm=Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}
		}

		$sView=Yii::app()->clientForm->getView();//запрашиваем имя текущего представления

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
