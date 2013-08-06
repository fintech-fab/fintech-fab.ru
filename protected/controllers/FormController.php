<?php

class FormController extends Controller
{
	public $showTopPageWidget = true;

	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array $aPost
		 * @var string $sView
		 */

		$client_id = $this->getClientId();

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oClientForm = Yii::app()->clientForm->getFormModel();

		/**
		 * AJAX валидация
		 */
		if (Yii::app()->clientForm->ajaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			echo IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		if ($aPost = Yii::app()->clientForm->getPostData()) //проверяем, был ли POST запрос
		{
			$oClientForm->attributes = $aPost; //передаем запрос в форму

			if ($oClientForm->validate()){
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oClientForm = Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}
		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if (Cookie::compareDataInCookie('client', 'client_id', $client_id)
			&& Yii::app()->session[get_class($oClientForm) . '_client_id'] == $client_id
		) {
			if (isset($oClientForm) && $oClientForm) {
				$sessionClientData = Yii::app()->session[get_class($oClientForm)];
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления

		$this->render($sView, array('oClientCreateForm' => $oClientForm));
	}

	/**
	 *  Переход на шаг $step
	 * @param int $step
	 */
	public function actionStep($step)
	{
		if ($step !== 0) {
			if (Yii::app()->session['done_steps'] < ($step - 1)) {
				Yii::app()->session['current_step'] = Yii::app()->session['done_steps'];
			} else {
				Yii::app()->session['done_steps'] = $step - 1;
				Yii::app()->session['current_step'] = $step - 1;
			}
			$this->redirect(Yii::app()->createUrl("form"));
		}

	}


	/**
	 * Загрузка фото
	 */

	public function actionIdentification()
	{
		$client_id = $this->getClientId();

		if (Yii::app()->session['current_step'] == 7) {

			$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $client_id . '/';

			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PHOTO . '.png';

			if ($this->checkFiles($aFiles)) {

				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
			$this->actionIndex();
		} else $this->actionIndex();
	}

	/**
	 * Загрузка документов
	 */
	public function actionDocuments()
	{

		$client_id = $this->getClientId();

		if (Yii::app()->session['current_step'] == 8) {

			$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $client_id . '/';

			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_LAST . '.png';



			if ($this->checkFiles($aFiles)) {
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг

			}
			$this->actionIndex();
		} else $this->actionIndex();
	}

	public function actionConfirmPhoneViaSms()
	{

		return;
	}

	private function checkFiles($aFiles)
	{
		if(!isset($aFiles)||gettype($aFiles)!='array') return false;

		foreach ($aFiles as $sFile) {
			if (!file_exists($sFile) || !getimagesize($sFile)) {
				return false;
			}
		}
		return true;
	}


	public function actionStart()
	{
		Yii::app()->clientForm->startNewForm();
		$this->redirect(Yii::app()->createUrl("form"));
	}

	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
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

	private function getClientId()
	{
		return Yii::app()->session['client_id'];
	}

	private function clearSession()
	{
		Yii::app()->session['current_step']=0;
		Yii::app()->session['done_steps']=0;

		/*
		Yii::app()->session['ClientSelectProductForm']=null;
		Yii::app()->session['ClientSelectGetWayForm']=null;
		Yii::app()->session['ClientPersonalDataForm']=null;
		Yii::app()->session['ClientAddressForm']=null;
		Yii::app()->session['ClientJobInfoForm']=null;
		Yii::app()->session['ClientSendForm']=null;
		*/
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
