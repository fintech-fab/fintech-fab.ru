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

		$clientData=new ClientData(); //объект CActiveRecord для записи в БД

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oForm=Yii::app()->clientForm->getFormModel();

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
				Yii::app()->clientForm->formDataProcess($clientData,$oForm);
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

	public function actionStep1()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 0)
		{
			return;
		}

		Yii::app()->session['current_step']='0';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStep2()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 1)
		{
			return;
		}

		Yii::app()->session['current_step']='1';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStep3()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 2)
		{
			return;
		}

		Yii::app()->session['current_step']='2';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStep4()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 3)
		{
			return;
		}

		Yii::app()->session['current_step']='3';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStep5()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 4)
		{
			return;
		}

		Yii::app()->session['current_step']='4';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStep6()//функция для тестирования, сбрасывает сессию
	{
		if(Yii::app()->session['done_steps'] < 5)
		{
			return;
		}

		Yii::app()->session['current_step']='5';

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
