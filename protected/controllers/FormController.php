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
		$client_id = Yii::app()->session['client_id'];

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oForm=Yii::app()->clientForm->getFormModel();

		/**
		 * AJAX валидация
		 */
		if(Yii::app()->clientForm->ajaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			echo IkTbActiveForm::validate($oForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($clientData,$oForm); //сохраняем полученные при ajax-запросе данные
			//TODO проверять безопасность данных перед записью, даже с учетом отсутствия валидации!!!
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		if($aPost=Yii::app()->clientForm->getPostData())//проверяем, был ли POST запрос
		{
			$oForm->attributes=$aPost; //передаем запрос в форму
			if(isset($oForm->go)&&$oForm->go=="1")
			{
				$client_id = Yii::app()->session['current_step']=0;
				$client_id = Yii::app()->session['done_steps']=0;
				$client_id = Yii::app()->session['form_complete']=true;
				$this->redirect(Yii::app()->createUrl("form/identification"));

			}
			elseif($oForm->validate())
			{
				Yii::app()->clientForm->formDataProcess($clientData,$oForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oForm=Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}
		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if(Cookie::compareDataInCookie('client','client_id',$client_id))
		{
			if(isset($oForm)&&$oForm)
			{
				$sessionClientData = Yii::app()->session[get_class($oForm)];
				$oForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView=Yii::app()->clientForm->getView();//запрашиваем имя текущего представления

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}

	public function actionStart()//функция для тестирования, сбрасывает сессию
	{
		Yii::app()->session['current_step']='';
		Yii::app()->session['done_steps']='';

		Yii::app()->session['client_id']='';
		Yii::app()->session['phone']='';

		$sView=Yii::app()->clientForm->getView();

		$oForm=Yii::app()->clientForm->getFormModel();

		$this->render($sView,array('oClientCreateForm'=>$oForm));
	}


	/**
	 *  Переход на шаг $step
	 *  @param int $step
	 */
	public function actionStep($step)
	{
		//$client_id = Yii::app()->session['current_step']=0;
		//$client_id = Yii::app()->session['done_steps']=0;
		if($step!==0)
		{
			if(Yii::app()->session['done_steps'] < ($step-1))
			{
				Yii::app()->session['current_step']=Yii::app()->session['done_steps'];
			}
			else
			{
				Yii::app()->session['current_step']=$step-1;
			}
			$this->redirect(Yii::app()->createUrl("form"));
		}

	}

	public function actionIdentification() {

		if(!Yii::app()->session['form_complete'])
		{
			$this->redirect(Yii::app()->createUrl("form"));
		}
		$this->render('identification');
	}

	/**
	 * Загрузка документов
	 */
	public function actionDocuments() {

		if(!Yii::app()->session['form_complete'])
		{
			$this->redirect(Yii::app()->createUrl("form"));
		}

		$this->render('documents');
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
