<?php

/**
 * Class FormController
 *
 * Контроллер заполнения анкеты клиента
 *
 */
class FormController extends Controller
{
	public $showTopPageWidget = false;


	public function actionIndex()
	{
		$this->index();
	}

	public function actionAjaxForm()
	{
		$this->index(true);
	}

	/**
	 * TODO этот экшен и его не-ajax аналог переделать, вынести смену шага в отдельный метод, его вызывать тут
	 *
	 * @param $step
	 */
	public function actionAjaxStep($step)
	{
		$step = (int)$step;

		$sSite = (SiteParams::getIsIvanovoSite()) ? ClientFormComponent::SITE2 : ClientFormComponent::SITE1;

		$iMinStep = ClientFormComponent::$aSteps[$sSite]['min'];
		$iMaxStep = ClientFormComponent::$aSteps[$sSite]['max'];

		// проверка, что шаг корректный
		if (($step < $iMinStep) || ($step > $iMaxStep)) {
			$step = ClientFormComponent::$aSteps[$sSite]['default'];
		}

		if ($step > 0) {
			$iDoneSteps = Yii::app()->clientForm->getDoneSteps();

			if ($iDoneSteps < ($step - 1)) {
				Yii::app()->clientForm->setCurrentStep($iDoneSteps);
			} else {
				Yii::app()->clientForm->setDoneSteps($step - 1);
				Yii::app()->clientForm->setCurrentStep($step - 1);
			}
		}
		$this->index(true);

	}

	/**
	 * @param bool $ajaxForm
	 */
	private function index($ajaxForm = null)
	{
		//проверяем, не нужно ли перейти к следующему шагу ничего не обрабатывая (для шагов без форм)
		if (Yii::app()->clientForm->tryGoNextStep()) {
			$this->redirect(Yii::app()->createUrl("/form"));
		}

		//проверяем, что для текущего сайта выбран продукт
		//если не выбран - то сбрасываем шаги
		if (!Yii::app()->clientForm->checkSiteSelectedProduct()) {
			Yii::app()->clientForm->resetSteps();
		}

		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array                    $aPost
		 * @var string                   $sView
		 */
		$iClientId = Yii::app()->clientForm->getClientId();

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oClientForm = Yii::app()->clientForm->getFormModel();

		/**
		 * AJAX валидация
		 */
		if (Yii::app()->clientForm->isNeedAjaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			//если для ajax-валидации пришла форма, соответствующая текущему шагу, то обработаем ее
			if ($sAjaxClass = Yii::app()->request->getParam('ajax') == get_class($oClientForm)) {
				$sEcho = IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
				Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
				echo $sEcho;
			}
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		$aPost = Yii::app()->clientForm->getPostData();

		if ($aPost) //проверяем, был ли POST запрос
		{

			$oClientForm->attributes = $aPost; //передаем запрос в форму

			if ($oClientForm->validate()) {

				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				//$oClientForm = Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
				if (!$ajaxForm) {
					//если не ajaxForm-запрос, то редиректим (чтобы по F5 страница просто обновилась, а не ругалась на POST)
					$this->redirect(Yii::app()->createUrl("/form"));
				} else {
					//если это ajaxForm-запрос, то заново после обработки данных получаем ID клиента и модель
					$iClientId = Yii::app()->clientForm->getClientId();
					$oClientForm = Yii::app()->clientForm->getFormModel();
				}
			} else {
				//если не удалось провалидировать, то все равно сохраним валидные данные
				//используем метод saveAjaxData т.к. он сохраняет только валидные данные
				Yii::app()->clientForm->saveAjaxData($oClientForm);
			}

		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if ($oClientForm
			&& Cookie::compareDataInCookie('client', 'client_id', $iClientId)
			&& Yii::app()->clientForm->getSessionFormClientId($oClientForm) == $iClientId
		) {
			if (!empty($oClientForm)) {
				$sessionClientData = Yii::app()->clientForm->getSessionFormData($oClientForm);
				//удаляем лишние данные перед загрузкой в форму (во избежание warning)
				unset($sessionClientData['client_id']);
				unset($sessionClientData['product']);
				unset($sessionClientData['channel_id']);
				unset($sessionClientData['entry_point']);
				unset($sessionClientData['flex_amount']);
				unset($sessionClientData['flex_time']);
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$aView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления
		$sView = $aView['view'];
		$sSubView = $aView['sub_view'];

		//TODO показ виджета сконфигурировать в массиве форм-компонента и отдавать через метод сюда
		if ($sView === 'client_select_product' || $sView === 'client_flexible_product') {
			$this->showTopPageWidget = true;
		}

		if (!$ajaxForm) {
			$this->render($sView, array('oClientCreateForm' => $oClientForm, 'sSubView' => $sSubView));
		} elseif ($sSubView) {
			//отключаем из вывода файлы скриптов во избежание проблем (они уже подключены на странице)
			Yii::app()->clientscript->scriptMap['jquery.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery.maskedinput.js'] = false;
			Yii::app()->clientscript->scriptMap['jquery.yiiactiveform.js'] = false;

			$this->renderPartial($sSubView, array('oClientCreateForm' => $oClientForm), false, true);
		}
	}

	//todo: убрать после НГ?
	public function actionShoppingNewYear()
	{
		Yii::app()->clientForm->goSelectProduct('Кредди 3000');
		$this->redirect('/form');
	}

	public function actionShopping()
	{
		Yii::app()->clientForm->goSelectProduct('Покупки');
		$this->redirect('/form');
	}

	public function actionSaveSelectedProduct()
	{
		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array                    $aPost
		 * @var string                   $sView
		 */
		//TODO перепроверить метод
		/*
		 * Запрашиваем у компонента форму выбора продукта
		 */
		$sModelName = Yii::app()->clientForm->getSelectProductModelName();
		$oClientForm = new $sModelName();

		$bIsAjax = Yii::app()->request->getIsAjaxRequest();
		$sAjaxClass = Yii::app()->request->getParam('ajax');

		if ($bIsAjax && ($sAjaxClass === $sModelName)) {
			$sEcho = IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveSelectedProduct($oClientForm); //сохраняем полученные при ajax-запросе данные
			echo $sEcho;
			Yii::app()->end();
		}

		$this->redirect('/form');
	}

	/**
	 *  Переход на шаг $step
	 * @param int $step
	 */
	public function actionStep($step)
	{
		$sSite = (SiteParams::getIsIvanovoSite()) ? ClientFormComponent::SITE2 : ClientFormComponent::SITE1;

		$iMinStep = ClientFormComponent::$aSteps[$sSite]['min'];
		$iMaxStep = ClientFormComponent::$aSteps[$sSite]['max'];

		// проверка, что шаг корректный
		if (($step < $iMinStep) || ($step > $iMaxStep)) {
			$step = ClientFormComponent::$aSteps[$sSite]['default'];
		}

		if ($step > 0) {
			$iDoneSteps = Yii::app()->clientForm->getDoneSteps();

			if ($iDoneSteps < ($step - 1)) {
				Yii::app()->clientForm->setCurrentStep($iDoneSteps);
			} else {
				Yii::app()->clientForm->setDoneSteps($step - 1);
				Yii::app()->clientForm->setCurrentStep($step - 1);
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 * Отправка SMS с кодом
	 */
	public function actionSendSmsCode()
	{
		// если в сессии телефона нет либо если полная форма не заполнена - редирект на form
		if (!Yii::app()->clientForm->getSessionPhone()) {
			$this->redirect(Yii::app()->createUrl("/form"));
		}

		// отправляем SMS с кодом. если $oAnswer !== true, то ошибка
		$oAnswer = Yii::app()->clientForm->sendSmsCode();

		// если были ошибки при отправке, то добавляем в сессию сообщение об ошибке
		if ($oAnswer !== true) {
			$aView = Yii::app()->clientForm->getView();
			$sView = $aView['view'];
			$sSubView = $aView['sub_view'];

			//устанавливаем в сессию ошибку, для вывода в представлении
			Yii::app()->session['error'] = $oAnswer;

			$this->render($sView, array(
					'sSubView'          => $sSubView,
					'oClientCreateForm' => Yii::app()->clientForm->getFormModel(),
				)
			);
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl("/form"));
	}

	/**
	 * проверка кода, введённого пользователем
	 */
	public function actionCheckSmsCode()
	{
		// забираем данные из POST и заносим в форму ClientConfirmPhoneViaSMSForm
		$aPostData = Yii::app()->request->getParam('ClientConfirmPhoneViaSMSForm');
		$iClientId = Yii::app()->clientForm->getClientId();

		// если не было POST запроса либо если флага, что SMS отправлялось, нет - перенаправляем на form
		if (empty($aPostData) || !Yii::app()->clientForm->getFlagSmsSent()) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		// сверяем код. если $oAnswer !== true, то ошибка
		$mAnswer = Yii::app()->clientForm->checkSmsCode($aPostData);

		// если был POST запрос и код неверен - добавляем текст ошибки к атрибуту
		if ($mAnswer !== true) {
			$oClientSmsForm = new ClientConfirmPhoneViaSMSForm();
			$oClientSmsForm->setAttributes($aPostData);
			$oClientSmsForm->addError('sms_code', $mAnswer);


			//получаем view для проверки смс-кода
			$aView = Yii::app()->clientForm->getView();
			$sView = $aView['view'];
			$sSubView = $aView['sub_view'];

			$this->render($sView, array(
				'sSubView' => $sSubView,
				'oClientCreateForm' => $oClientSmsForm,
			));
			//если число попыток превышено, то чистим сессию
			if (Yii::app()->clientForm->isSmsCodeTriesExceed()) {
				Yii::app()->clientForm->clearClientSession();
			}
			Yii::app()->end();
		} else {
			//если код верный, то берем данные из БД
			$aClientData = ClientData::getClientDataById($iClientId);
			//отправляем в API данные клиента, и если клиент успешно создан
			if (Yii::app()->clientForm->sendClientToApi($aClientData)) {

				//автоматический логин юзера в личный кабинет
				$oLogin = new AutoLoginForm(); //модель для автоматического логина в систему
				$oLogin->setAttributes(array('username' => $aClientData['phone'])); //устанавливаем аттрибуты логина
				//Yii::app()->user->setStateKeyPrefix('_account'); //префикс для модуля account
				if ($oLogin->validate() && $oLogin->login()) {
					//сохраняем данные перед редиректом в ЛК
					if (!empty($aClientData['product'])) {
						Yii::app()->user->setState('product', $aClientData['product']);
					}
					if (!empty($aClientData['channel_id'])) {
						Yii::app()->user->setState('channel_id', $aClientData['channel_id']);
					}
					if (!empty($aClientData['flex_amount'])) {
						Yii::app()->user->setState('flex_amount', $aClientData['flex_amount']);
					}
					if (!empty($aClientData['flex_time'])) {
						Yii::app()->user->setState('flex_time', $aClientData['flex_time']);
					}
					Yii::app()->user->setState('new_client', true);

					$this->redirect(Yii::app()->createUrl('form/success'));
				}


			} else {
				//если не удалось создать нового клиента, то выводим ошибку
				Yii::app()->session['error'] = 'Ошибка! Проверьте правильность введенных данных.';
				Yii::app()->clientForm->setFlagSmsSent(false); //сбрасываем флаг отправленного СМС
				$this->actionStep(1); //переходим на шаг 1
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	public function actionSuccess()
	{
		$bNewClient = Yii::app()->user->getState('new_client', false);

		// если не новый клиент, перемещаем на /form
		if (!$bNewClient) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		// очищаем сессию (данные формы и прочее)
		Yii::app()->clientForm->clearClientSession();

		$this->render('form_sent',
			array(
				'sRedirectUri' => Yii::app()->createUrl('account/doSubscribe'),
			)
		);
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
