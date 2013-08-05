<?php

class FormController extends Controller
{
	public $showTopPageWidget=true;

	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array $aPost
		 * @var string $sView
		 */

		$oClientForm = new ClientConfirmPhoneViaSMSForm();
		$this->render('client_confirm_phone_via_sms',array('oClientCreateForm'=>$oClientForm));
		return;

		$client_id = Yii::app()->session['client_id'];

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oClientForm=Yii::app()->clientForm->getFormModel();

		/**
		 * AJAX валидация
		 */
		if(Yii::app()->clientForm->ajaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			echo IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		if($aPost=Yii::app()->clientForm->getPostData())//проверяем, был ли POST запрос
		{
			$oClientForm->attributes=$aPost; //передаем запрос в форму

			if(isset($oClientForm->go)&&$oClientForm->go=="1")
			{
				Yii::app()->session['identification_step']=1;

				$this->redirect(Yii::app()->createUrl("form/identification"));
			}
			elseif($oClientForm->validate())
			{
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oClientForm=Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}

		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if(Cookie::compareDataInCookie('client','client_id',$client_id))
		{
			if(isset($oClientForm)&&$oClientForm)
			{
				$sessionClientData = Yii::app()->session[get_class($oClientForm)];
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView=Yii::app()->clientForm->getView();//запрашиваем имя текущего представления

		$this->render($sView,array('oClientCreateForm'=>$oClientForm));
	}

	/**
	 *  Переход на шаг $step
	 *  @param int $step
	 */
	public function actionStep($step)
	{
		if($step!==0)
		{
			if(Yii::app()->session['done_steps'] < ($step-1))
			{
				Yii::app()->session['current_step']=Yii::app()->session['done_steps'];
			}
			else
			{
				Yii::app()->session['done_steps']=$step-1;
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
		if(Yii::app()->session['identification_step'] != 1)
		{
			$this->redirect(Yii::app()->createUrl("form"));
		}

		$this->render('identification');
	}

	public function actionConfirmPhoneViaSms() {

		return;
	}

	/**
	 * Загрузка документов
	 */
	public function actionDocuments() {

		if(!Yii::app()->session['form_complete'])
		{
			$this->redirect(Yii::app()->createUrl("form"));
		}

		if(Yii::app()->session['identification_step'] != 1)
		{
			$this->redirect(Yii::app()->createUrl("form"));
		}

		Yii::app()->session['identification_step'] = 2;

		$this->render('documents');
	}

	public function actionStart(){
		Yii::app()->clientForm->startNewForm();
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

	public function actionAjaxSendSms()
	{
		// если ajax-данные
		if(Yii::app()->request->isAjaxRequest){

			$client_id = Yii::app()->session['client_id'];

			$oClientForm=new ClientConfirmPhoneViaSMSForm();

			// генерация рандомного кода
			// сеет с микросекундами
			function make_seed() {
				list($usec, $sec) = explode(' ', microtime());
				return (float) $sec + ((float) $usec * 100000);
			}

			mt_srand(make_seed());
			$generated_code = substr(mt_rand(),0,6);

			// запись кода в базу
			ClientData::saveClientDataById($oClientForm, $client_id);

			// ответ пользователю... удалить потом, это для проверки
			echo CHtml::encode($generated_code);

			Yii::app()->end();
		}
	}

	public function actionSendCode()
	{
		$model=new ClientConfirmPhoneViaSMSForm();

		// данные не ajax
		if(isset($_POST['ClientConfirmPhoneViaSMSForm']))
		{
			// проверить, что данные присланные и данные из базы по этому телефону совпадают
			// ??
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
