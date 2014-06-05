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


	public function actionResendSms()
	{
		$iResendTime = $this->getResendTime('sms');
		$sResendText = 'Повторно запросить SMS с кодом можно через:';

		if ($iResendTime <= 0) {
			$mResendResult = Yii::app()->clientForm->sendCodes(true, false, true);
			$this->setResendTime('sms', SiteParams::getTime());
			if ($mResendResult === true) {
				$sResendText = 'Код успешно отправлен! ' . $sResendText;
			} else {
				$sResendText = $mResendResult . ' ' . $sResendText;;
			}
		}

		$this->widget('application.modules.account.components.ResendCodeWidget',
			array(
				'sUrl'        => '/form/resendSms',
				'sId'         => 'Sms',
				'sResendText' => $sResendText,
				'iTime'       => $this->getResendTime('sms'),
			)
		);
		Yii::app()->end();
	}

	public function actionResendEmail()
	{
		$iResendTime = $this->getResendTime('email');
		$sResendText = 'Повторно запросить SMS с кодом можно через:';

		if ($iResendTime <= 0) {
			$mResendResult = Yii::app()->clientForm->sendCodes(false, true, true);
			$this->setResendTime('email', SiteParams::getTime());
			if ($mResendResult === true) {
				$sResendText = 'Код успешно отправлен! ' . $sResendText;
			} else {
				$sResendText = $mResendResult . ' ' . $sResendText;;
			}
		}

		$this->widget('application.modules.account.components.ResendCodeWidget',
			array(
				'sUrl'        => '/form/resendEmail',
				'sId'         => 'Email',
				'sResendText' => $sResendText,
				'iTime'       => $this->getResendTime('email'),
			)
		);
		Yii::app()->end();
	}

	public function actionIndex()
	{
		$this->index();
	}

	public function actionAjaxForm()
	{
		$this->index(true);
	}

	/**
	 * @return array
	 */
	public function filters()
	{
		return array(
			array(
				'ext.linkprofit.LinkprofitFilter',
			),
		);
	}

	/**
	 * TODO этот экшен и его не-ajax аналог переделать, вынести смену шага в отдельный метод, его вызывать тут
	 *
	 * @param $step
	 */
	public function actionAjaxStep($step)
	{
		$step = (int)$step;

		$sSite = Yii::app()->clientForm->getSiteConfigName();

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
		Yii::app()->clientForm->checkSiteSelectedProduct();

		//запустим проверку типа регистрации, она переключит "режим" анкеты, если потребуется
		Yii::app()->clientForm->checkRegistrationType();

		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array                    $aPost
		 * @var string                   $sView
		 */

		//пробуем получить метод, который требуется вызвать на этом шаге, если он задан
		$sMethod = Yii::app()->clientForm->getControllerMethod();
		//если метод указан в конфиге и такой метод в контроллере существует, выполним его
		if ($sMethod && function_exists($this->$sMethod())) {
			$this->$sMethod();
			//обязательно завершать приложение после выполнения метода, если сам метод этого не сделал
			Yii::app()->end();
		}

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
			echo Yii::app()->clientForm->doAjaxValidation($oClientForm);
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		$aPost = Yii::app()->clientForm->getPostData();
		if ($aPost) //проверяем, был ли POST запрос
		{
			$oClientForm->setAttributes($aPost); //передаем запрос в форму
			if ($oClientForm->validate()) {
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг

				if (!$ajaxForm) {
					//если не ajaxForm-запрос, то редиректим (чтобы по F5 страница просто обновилась, а не ругалась на POST)
					$this->redirect(Yii::app()->createUrl("/form"));
				} else {
					//если это ajaxForm-запрос, то заново после обработки данных получаем ID клиента и модель
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
			//&& Cookie::compareDataInCookie('client', 'client_id', $iClientId)
			//&& Yii::app()->clientForm->getSessionFormClientId($oClientForm) == $iClientId
		) {
			if (!empty($oClientForm)) {
				$aSessionClientData = Yii::app()->clientForm->getSessionFormData($oClientForm);
				//удаляем лишние данные перед загрузкой в форму (во избежание warning)
				if (is_array($aSessionClientData)) {
					$aSessionClientData = array_intersect_key($aSessionClientData, array_flip($oClientForm->attributeNames()));
				}

				$oClientForm->setAttributes($aSessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sLayout = Yii::app()->clientForm->getLayout(); //запрашиваем лэйаут

		if ($sLayout) {
			$this->layout = $sLayout;
		}

		$aView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления
		$sView = $aView['view'];
		$sSubView = $aView['sub_view'];

		$this->showTopPageWidget = Yii::app()->clientForm->isTopPageWidgetVisible();

		if (!$ajaxForm) {
			$this->render($sView, array('oClientCreateForm' => $oClientForm, 'sSubView' => $sSubView));

		} elseif ($sSubView) {
			$this->disableJs();

			$this->renderPartial($sSubView, array('oClientCreateForm' => $oClientForm), false, true);
		}
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
	 *
	 * @param int $step
	 */
	public function actionStep($step)
	{
		$sSite = Yii::app()->clientForm->getSiteConfigName();

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
	public function actionSendCodes()
	{
		// если в сессии телефона нет либо если полная форма не заполнена - редирект на form
		if (!Yii::app()->clientForm->getSessionPhone()) {
			//TODO тут сделать проверку, что клиент реально на нужном шаге!!!!!
			$this->redirect(Yii::app()->createUrl("/form"));
		}

		//проверим, что форма заполнена
		if (!Yii::app()->clientForm->checkFormComplete()) {
			$this->redirect(Yii::app()->createUrl("/form"));
		}

		$iClientId = Yii::app()->clientForm->getClientId();

		if (!$iClientId) {
			Yii::app()->clientForm->resetSteps();
			$this->redirect(Yii::app()->createUrl("/form"));
		}
		//если клиент запрашивает СМС, значит, заполнил анкету полностью
		$aData['complete'] = 1;
		ClientData::saveClientDataById($aData, $iClientId);

		// отправляем SMS с кодом. если $oAnswer !== true, то ошибка
		$oAnswer = Yii::app()->clientForm->sendCodes();

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
	public function actionCheckCodes()
	{
		// забираем данные из POST и заносим в форму ClientConfirmPhoneAndEmailForm
		$aPostData = Yii::app()->request->getParam('ClientConfirmPhoneAndEmailForm');
		$iClientId = Yii::app()->clientForm->getClientId();

		// если не было POST запроса либо если флага, что SMS отправлялось, нет - перенаправляем на form
		if (empty($aPostData) || !Yii::app()->clientForm->getFlagCodesSent()) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		$oCheckResult = new stdClass();
		$oCheckResult->bEmailError = false;
		$oCheckResult->bSmsError = false;

		// сверяем код. если $oAnswer !== true, то ошибка
		$mAnswer = Yii::app()->clientForm->checkCodes($aPostData, $oCheckResult);

		// если был POST запрос и код неверен - добавляем текст ошибки к атрибуту
		if ($mAnswer !== true) {
			$oClientSmsForm = new ClientConfirmPhoneAndEmailForm();
			$oClientSmsForm->setAttributes($aPostData);

			if ($oCheckResult->bSmsError) {
				$oClientSmsForm->addError('sms_code', $mAnswer);
			}
			if ($oCheckResult->bEmailError) {
				$oClientSmsForm->addError('email_code', $mAnswer);
			}


			//получаем view для проверки смс-кода
			$aView = Yii::app()->clientForm->getView();
			$sView = $aView['view'];
			$sSubView = $aView['sub_view'];

			$this->render($sView, array(
				'sSubView'          => $sSubView,
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

			//отправляем в API данные клиента
			$bRegisterSuccess = Yii::app()->clientForm->sendClientToApi($aClientData);

			//если клиент успешно создан
			if ($bRegisterSuccess) {

				// подтверждение по SMS и регистрация выполнены успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
				// ставим этот флаг только после успешной регистрации, т.е. он не будет поставлен в случае повторного использования номера
				$aData['flag_sms_confirmed'] = 1;
				ClientData::saveClientDataById($aData, $iClientId);
				//автоматический логин юзера в личный кабинет
				$oLogin = new AutoLoginForm(); //модель для автоматического логина в систему
				$oLogin->setAttributes(array('username' => $aClientData['phone'])); //устанавливаем аттрибуты логина
				if ($oLogin->validate() && $oLogin->login()) {
					//сохраняем данные перед редиректом в ЛК
					Yii::app()->clientForm->saveDataBeforeRedirectToAccount($aClientData);

					//установим информацию о завершенной регистрации перед редиректом
					Yii::app()->clientForm->setRegisterComplete();
					Yii::app()->clientForm->clearClientSession();
					$this->redirect(Yii::app()->createUrl('form/fastSuccess'));
				}


			} elseif (Yii::app()->adminKreddyApi->getIsClientExistsError()) {
				$this->render('client_exists');
			} else {
				//если не удалось создать нового клиента, то выводим ошибку
				Yii::app()->session['error'] = 'По указанным Вами данным невозможно подключить личный кабинет. Возможно, вы уже зарегистрированы в системе Кредди. Обратитесь в контактный центр';
				Yii::app()->clientForm->setFlagCodesSent(false); //сбрасываем флаг отправленного СМС
				Yii::app()->clientForm->clearClientSession(); //чистим сессию
				$this->actionStep(1); //переходим на шаг 1
				$this->render('error');
			}
		}

		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 *
	 */
	public function continueRegSuccess()
	{
		if (!Yii::app()->clientForm->isContinueReg()) {
			$this->redirect(Yii::app()->createUrl('/form'));
		}

		$iClientId = Yii::app()->clientForm->getClientId();

		//берем данные из БД
		$aClientData = ClientData::getClientDataById($iClientId);

		//отправляем в API данные клиента
		$bRegisterSuccess = Yii::app()->clientForm->updateFastRegClient($aClientData);

		//если клиент успешно обновил данные анкеты
		if ($bRegisterSuccess) {

			Yii::app()->clientForm->saveDataBeforeRedirectToAccount($aClientData);

			//отключаем режим продолжения регистрации
			Yii::app()->clientForm->setContinueReg(false);

			//обновим в таблице статус записи
			$oClientData = ClientData::model()->scopeConfirmedPhone($aClientData['phone'])->find();
			$oClientData->complete = 1;
			$oClientData->save();

			//установим информацию о завершенной регистрации перед редиректом
			Yii::app()->clientForm->setRegisterComplete();
			Yii::app()->clientForm->clearClientSession(); //чистим сессию
			$this->redirect(Yii::app()->createUrl('form/success'));

		} else {
			//если не удалось создать нового клиента, то выводим ошибку
			Yii::app()->session['error'] = 'По указанным Вами данным невозможно подключить личный кабинет. Возможно, вы уже зарегистрированы в системе КРЕДДИ. Обратитесь в контактный центр.';
			Yii::app()->clientForm->clearClientSession(); //чистим сессию
			$this->actionStep(1); //переходим на шаг 1
		}
	}

	/**
	 * Страница поздравления клиента при полном заполнении анкеты
	 */
	public function actionSuccess()
	{
		$sRedirectUrl = Yii::app()->createUrl('account/doSubscribe');
		$this->success('form_success', $sRedirectUrl);
	}

	/**
	 * Страница поздравления клиента при законченной быстрой регистрации
	 */
	public function actionFastSuccess()
	{
		$sRedirectUrl = Yii::app()->createUrl('account/doSubscribe');
		$this->success('form_sent', $sRedirectUrl);
	}

	/**
	 * Страница поздравления клиента
	 *
	 *
	 * @param $sTemplateName string Имя шаблона для рендеринга
	 * @param $sRedirectUrl  string Страница на которую будет перенаправлен пользователь
	 */
	private function success($sTemplateName, $sRedirectUrl)
	{
		$bNewClient = Yii::app()->user->getState('new_client', false);

		$aRegisterComplete = Yii::app()->clientForm->getRegisterComplete();

		// если не новый клиент, перемещаем на /form
		if (!$bNewClient || !$aRegisterComplete) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		// очищаем сессию (данные формы и прочее)
		Yii::app()->clientForm->clearClientSession();

		$aRegisterComplete = Yii::app()->clientForm->getRegisterComplete();

		$sSuccessYmGoal = isset($aRegisterComplete['sYmGoal']) ?
			$aRegisterComplete['sYmGoal'] :
			'';

		//сотрем информацию о завершении регистрации
		Yii::app()->clientForm->setRegisterComplete(false);

		$this->render($sTemplateName,
			array(
				'sRedirectUri'   => $sRedirectUrl,
				'sSuccessYmGoal' => $sSuccessYmGoal
			)
		);
	}

	private function disableJs()
	{
		//отключаем из вывода файлы скриптов во избежание проблем (они уже подключены на странице)
		Yii::app()->clientscript->scriptMap['jquery.js'] = false;
		Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
		Yii::app()->clientscript->scriptMap['jquery.maskedinput.js'] = false;
		Yii::app()->clientscript->scriptMap['jquery.maskedinput.min.js'] = false;
		Yii::app()->clientscript->scriptMap['jquery.yiiactiveform.js'] = false;
		Yii::app()->clientscript->scriptMap['jquery.yiiactiveform.min.js'] = false;
	}

	/**
	 * @param $sName
	 *
	 * @return int
	 */
	public function getResendTime($sName)
	{
		$iCurTime = time();

		if (empty(Yii::app()->session[$sName . 'ResendTime'])) {
			$iLeftTime = $iCurTime;
			$this->setResendTime($sName, $iLeftTime);
		} else {
			$iLeftTime = Yii::app()->session[$sName . 'ResendTime'];
		}

		$iLeftTime = $iCurTime - $iLeftTime;
		$iLeftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $iLeftTime;


		return $iLeftTime;
	}

	/**
	 * @param $sName
	 * @param $iTime
	 */
	public function setResendTime($sName, $iTime)
	{
		Yii::app()->session[$sName . 'ResendTime'] = $iTime;
	}

	/*

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
