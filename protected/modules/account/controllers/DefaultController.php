<?php

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{

	public $layout = '/layouts/column2';
	public $smsState;

	/**
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',
				'actions' => array('login', 'resetPassword', 'resetPassSendPass', 'resetPassSmsSentSuccess', 'resetPasswordResendSmsCode'),
				'users'   => array('*'),
			),
			array(
				'allow',
				'actions' => array(
					'logout', 'index', 'history', 'identify', 'identifySite', 'identifyPhoto', 'identifyApp', 'checkSmsPass', 'smsPassAuth',
					'sendSmsPass', 'smsPassResend', 'subscribe', 'selectChannel', 'doSubscribe', 'doSubscribeCheckSmsCode',
					'doSubscribeSmsConfirm', 'loan', 'doLoan', 'doLoanSmsConfirm', 'doLoanCheckSmsCode',
					'addCard', 'verifyCard', 'successCard', 'refresh',
					'changePassport', 'changePassportSendSmsCode', 'changePassportCheckSmsCode',
					'changeEmail', 'changeEmailSendSmsCode', 'changeEmailCheckSmsCode',
					'changeNumericCode', 'changeNumericCodeSendSmsCode', 'changeNumericCodeCheckSmsCode',
					'changeSecretQuestion', 'changeSecretQuestionSendSmsCode', 'changeSecretQuestionCheckSmsCode',
					'changeSmsAuthSetting', 'changeSmsAuthSettingSendSmsCode', 'changeSmsAuthSettingCheckSmsCode',
					'changePassword', 'changePasswordSendSmsCode', 'changePasswordCheckSmsCode',
					'cancelRequest',
					'returnFrom3DSecurity',
					'continueForm',
					'goIdentify',
					'takeLoan', 'takeLoanCheckSmsCode',
				),
				'users'   => array('@'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * @param CAction $aAction
	 *
	 * @return bool|void
	 */
	protected function beforeAction(\CAction $aAction)
	{
		$sActionId = $this->action->getId();

		$aActionsNotNeedAuth = array(
			'login',
			'resetPassword',
			'resetPassSendPass',
			'resetPassSmsSentSuccess',
			'resetPasswordResendSmsCode'
		);

		//если действие не в списке не требующих авторизации, то проверяем статус авторизации
		// проверка авторизации, логаут в случае если не авторизован
		if (!in_array($sActionId, $aActionsNotNeedAuth)) {
			if (!Yii::app()->adminKreddyApi->getIsAuth()) {
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->user->loginUrl);
			} elseif (Yii::app()->adminKreddyApi->getIsNeedRedirect()) {
				$this->redirect(SiteParams::getRedirectUrlForAccount('mainUrl'));
			}
		}

		//список действий, доступных для клиента, прошедшего быструю регистрацию
		$aActionsForFastRegUser = array(
			'index',
			'history',
			'login',
			'logout',
			'continueForm',
			'resetPassword',
			'resetPassSendPass',
			'resetPassSmsSentSuccess',
			'resetPasswordResendSmsCode'
		);
		//если данный клиент прошел быструю регистрацию
		if (Yii::app()->adminKreddyApi->isFastReg()) {
			//но вызванное действие не входит в доступные
			if (!in_array($sActionId, $aActionsForFastRegUser)) {
				//покажем приглашение к продолжению регистрации
				$this->render('need_continue_reg');
				Yii::app()->end();
			}
		}


		return parent::beforeAction($aAction);
	}

	/**
	 *
	 */
	public function actionRefresh()
	{
		Yii::app()->end();
	}

	/**
	 * Главная страница личного кабинета
	 */
	public function actionIndex()
	{
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account'));

		//выбираем папку представления в зависимости от статуса СМС-авторизации и статуса регистрации
		//если клиент прошел быструю регистрацию не не заполнил анкеты
		if (Yii::app()->adminKreddyApi->isFastReg()) {
			$sIndexView = 'index_fast_reg/index';
		} elseif (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$sIndexView = 'index_is_sms_auth/index';
		} else {
			$sIndexView = 'index_not_sms_auth/index';
		}

		// выбираем представление в зависимости от статуса подписки
		if (Yii::app()->adminKreddyApi->getSubscriptionProduct()) { //если подписка есть
			if (Yii::app()->adminKreddyApi->getMoratoriumLoan()
			) { // если есть мораторий на заём
				$sClientInfoView = 'loan_moratorium';
			} else { //если подписка есть
				$sClientInfoView = 'is_subscription';
			}
		} else { // нет подписки
			if (Yii::app()->adminKreddyApi->getMoratoriumSubscriptionLoan()
			) { // если есть мораторий на подписку/скоринг или заём
				//TODO вынести в константы типы вьюх
				$sClientInfoView = 'subscription_moratorium';
			} elseif (Yii::app()->adminKreddyApi->getSubscriptionRequestName()) { //если подписка "висит" на скоринге
				$sClientInfoView = 'subscription_scoring';
			} else { // можно оформить новый Пакет
				$sClientInfoView = 'new_subscription_available';
			}
		}

		$sIdentifyRender = '';

		if (Yii::app()->adminKreddyApi->checkIsNeedIdentify()) {
			$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
			if ($aGetIdent) {
				$oIdentify = new VideoIdentifyForm();
				$oIdentify->setAttributes($aGetIdent);
				$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/");
				//выводим форму отправки на идентификацию
				$sIdentifyRender = $this->renderPartial('index_need_identify', array('model' => $oIdentify), true);
			}
		}

		/**
		 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
		 */
		$oSmsPassForm = new SMSPasswordForm('sendRequired');
		$sPassFormRender = $this->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), true);
		//$sClientInfoRender = $this->renderPartial($sClientInfoView, array(), true);

		$bIsPossibleDoLoan = Yii::app()->adminKreddyApi->checkLoan();

		$this->render($sIndexView, array(
				'sClientInfoView' => $sClientInfoView,
				'sPassFormRender' => $sPassFormRender,
				'sIdentifyRender' => $sIdentifyRender,
				'bIsPossibleDoLoan' => $bIsPossibleDoLoan,
			)
		);

	}

	/**
	 * Заполнение анкеты клиента со статусом "быстрая регистрация"
	 */
	public function actionContinueForm()
	{
		//если клиент не является прошедшим только быструю регистрацию, то не пускаем
		if (!Yii::app()->adminKreddyApi->isFastReg()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$sPhone = Yii::app()->user->getId();
		/**найдем клиента в таблице регистрации по номеру телефона,
		 * т.к. этот клиент зарегистрирован, надо искать с флагом "телефон подтвержден по смс"
		 */
		$oClientData = ClientData::model()->scopeConfirmedPhone($sPhone)->find();

		//если вдруг запись почему-то не найдена (такого быть не может при корректной работе системы), ругаемся ошибкой
		if (!$oClientData) {
			Yii::app()->session['error'] = 'Произошла ошибка: не удалось перейти к заполнению анкеты.
			 Попробуйте повторить через несколько минут.';
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		//снимаем флаг "анкеты заполнена", чтобы не создавалась новая запись
		$oClientData->complete = 0;
		//обновим ФИО в базе, на случай если уже стерто
		$aClientData = Yii::app()->adminKreddyApi->getFullClientData();
		$oClientData->setAttributes($aClientData);
		$oClientData->save();

		//создаем клиенту куку, которая позволит продолжить регистрацию на сайте
		$aCookieData = array('client_id' => $oClientData->client_id, 'phone' => $sPhone);
		Cookie::saveDataToCookie('client', $aCookieData);
		//пишем в сессию ID клиента
		Yii::app()->session['client_id'] = $oClientData->client_id;

		//ставим клиенту флаг "продолжает регистрацию"
		Yii::app()->clientForm->setContinueReg(true);
		//сбросим шаги
		Yii::app()->clientForm->resetSteps();
		//запоминаем все данные в сессию

		if (isset($aClientData['birthday'])) {
			$aClientData['birthday'] = date('d.m.Y', SiteParams::strtotime($aClientData['birthday']));
		}
		if (isset($aClientData['passport_date'])) {
			$aClientData['passport_date'] = date('d.m.Y', SiteParams::strtotime($aClientData['passport_date']));
		}
		Yii::app()->clientForm->setFastRegClientSession($aClientData, $oClientData->client_id);

		//отправляем на форму регистрации
		$this->redirect(Yii::app()->createUrl('/form'));

	}

	public function actionIdentify()
	{
		$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
		if ($aGetIdent) {

			$oIdentify = new VideoIdentifyForm();
			$oIdentify->setAttributes($aGetIdent);
			$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/");
			//выводим форму отправки на идентификацию
			$this->render('identify', array('model' => $oIdentify));
		}
	}

	public function actionIdentifySite()
	{
		$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
		if ($aGetIdent) {

			$oIdentify = new VideoIdentifyForm();
			$oIdentify->setAttributes($aGetIdent);
			$oIdentify->type = 1;
			$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/");
			//выводим форму отправки на идентификацию
			$this->render('identify_site', array('model' => $oIdentify));
		}
	}

	public function actionIdentifyPhoto()
	{
		$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
		if ($aGetIdent) {

			$oIdentify = new VideoIdentifyForm();
			$oIdentify->setAttributes($aGetIdent);
			$oIdentify->type = 2;
			$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/");
			//выводим форму отправки на идентификацию
			$this->render('identify_photo', array('model' => $oIdentify));
		}
	}

	public function actionIdentifyApp()
	{
		$this->render('identify_app');
	}

	/**
	 * История операций
	 */
	public function actionHistory()
	{
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account/history'));

		//получаем историю операций из API
		$oHistoryDataProvider = Yii::app()->adminKreddyApi->getHistoryDataProvider();

		//выбираем представление в зависимости от статуса СМС-авторизации
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$sView = 'history_is_sms_auth';
		} else {
			$sView = 'history_not_sms_auth';
		}
		/**
		 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
		 */
		$oSmsPassForm = new SMSPasswordForm('sendRequired');
		$sPassFormRender = $this->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), true, false);
		$this->render($sView, array('sPassFormRender' => $sPassFormRender, 'historyProvider' => $oHistoryDataProvider));
	}

	public function actionReturnFrom3DSecurity()
	{
		$this->redirect(Yii::app()->createUrl('/account/verifyCard'));
	}

	/**
	 * Привязка банковской карты
	 *
	 */
	public function actionAddCard()
	{
		$sError = null;

		$oCardStatus = Yii::app()->adminKreddyApi->checkVerifyCardStatus();

		if (Yii::app()->adminKreddyApi->isCardVerifyNeedAdditionalFields()) {
			$oCardForm = new AddCardForm('additionalFields');
		} else {
			$oCardForm = new AddCardForm();
		}

		//проверяем, может ли клиент сделать еще одну попытку привязки карты
		if (!AntiBotComponent::getIsAddCardCanRequest(Yii::app()->user->getId())) {
			$sError = AdminKreddyApiComponent::C_CARD_ADD_TRIES_EXCEED;
			$this->render('card/add_card', array('model' => $oCardForm, 'sError' => $sError));
			Yii::app()->end();
		}

		//проверяем, не находится ли карта на верификации, если да - отправляем на страницу верификации
		if ($oCardStatus->bCardCanVerify) {
			$this->redirect(Yii::app()->createUrl('/account/verifyCard'));
		}

		//если пришел POST-запрос
		if (Yii::app()->request->isPostRequest) {
			Yii::app()->user->getFlash('warning'); //удаляем warning


			$aPostData = Yii::app()->request->getParam('AddCardForm');
			$oCardForm->setAttributes($aPostData);
			//валидируем полученные данные
			if ($oCardForm->validate()) {
				//отправляем данные в API на проверку
				$bAddCardOk = Yii::app()->adminKreddyApi->addClientCard(
					$oCardForm
				);
				//если удалось отправить карту на проверку
				if ($bAddCardOk) {
					//проверяем, включена ли верификация в admin.kreddy
					if (Yii::app()->adminKreddyApi->checkCardVerifyExists()) {
						//если да - отправляем юзера на страницу верификации карты для ввода заблокированной суммы
						$this->redirect(Yii::app()->createUrl('/account/verifyCard'));
					} else {
						//иначе ставим флаг "успешно верифицирована" и отправляем туда же для получения сообщение
						//об успешной привязке
						Yii::app()->user->setState('verifyCardSuccess', true);
						$this->redirect(Yii::app()->createUrl('/account/verifyCard'));
					}
				} else {
					//если проверка не пройдена
					//записываем ошибку в антибот
					AntiBotComponent::addCardError(Yii::app()->user->getId());
					//ругаемся ошибкой
					$sError = Yii::app()->adminKreddyApi->getLastMessage();
					if (empty($sError)) {
						$sError = AdminKreddyApiComponent::ERROR_MESSAGE_UNKNOWN;
					}
				}
			}
		}

		$this->render('card/add_card', array('model' => $oCardForm, 'sError' => $sError));
	}

	/**
	 * Верификация банковской карты
	 */
	public function actionVerifyCard()
	{
		Yii::app()->user->getFlash('warning'); //удаляем warning

		//если есть статус "верификация карты успешно выполнена" то рендерим соответствующее представление
		if (Yii::app()->user->getState('verifyCardSuccess')) {
			$this->render('card/success', array(
				'sMessage' => AdminKreddyApiComponent::C_CARD_SUCCESSFULLY_VERIFIED,
			));
			Yii::app()->user->setState('verifyCardSuccess', null);
			Yii::app()->end();
		}

		//если нельзя провести верификацию карты то отправляем на форму добавления карты
		$oCardStatus = Yii::app()->adminKreddyApi->checkVerifyCardStatus();

		if (!$oCardStatus->bCardCanVerify) {
			if ($oCardStatus->bCardVerify3DsError) {
				Yii::app()->user->setFlash('error', AdminKreddyApiComponent::C_CARD_VERIFY_ERROR_3DS);
			} else {
				/**
				 * если в куках написано, что карта отправлена на верификацию,
				 * но API говорит что верифицироваться нельзя, то пишем user->setFlash сообщение
				 * и удаляем куку
				 */
				Cookie::compareDataInCookie('cardVerify', 'onVerify', true);
				Yii::app()->user->setFlash('error', AdminKreddyApiComponent::C_CARD_VERIFY_EXPIRED);
			}
			Cookie::saveDataToCookie('cardVerify', array('onVerify' => false));


			$this->redirect($this->createUrl('/account/addCard'));
		}

		$sVerify3DHtml = $oCardStatus->sCardVerify3DHtml;
		$bNeedWait = $oCardStatus->bCardVerifyNeedWait;

		if (!Yii::app()->request->isPostRequest &&
			$sVerify3DHtml
		) {
			echo $sVerify3DHtml;
			Yii::app()->end();
		} elseif ($bNeedWait) {
			$this->render('card/verify_3d');
			Yii::app()->end();
		}

		$oVerifyForm = new VerifyCardForm();
		//если пришел POST-запрос, то пробуем верифицировать карту с полученными данными
		if (Yii::app()->request->isPostRequest) {
			$aPostData = Yii::app()->request->getParam('VerifyCardForm');

			$oVerifyForm->setAttributes($aPostData);
			//валидируем, в процессе валидации проводится преобразование дроби с запятой на точку
			if ($oVerifyForm->validate()) {
				//если ОК то отправляем в API на проверку
				$bVerify = Yii::app()->adminKreddyApi->verifyClientCard($oVerifyForm->sCardVerifyAmount);
				if ($bVerify) {
					//удаляем куку "карта отправлена на верификацию"
					Cookie::saveDataToCookie('cardVerify', array('onVerify' => false));

					if (Yii::app()->user->getState('new_client')) {
						Yii::app()->user->setFlash('success', AdminKreddyApiComponent::C_CARD_SUCCESSFULLY_VERIFIED);
						$this->redirect(Yii::app()->user->getReturnUrl());
					}

					//ставим статус успешной верификации карты
					Yii::app()->user->setState('verifyCardSuccess', true);
					//обновляем страницу
					$this->refresh();
				} else {
					//ругаемся ошибкой
					$oVerifyForm->addError('sCardVerifyAmount', Yii::app()->adminKreddyApi->getLastMessage());
				}

			}
		}

		$this->render('card/verify_card', array('model' => $oVerifyForm));
	}

	/**
	 * Смена данных паспорта, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangePassport()
	{
		//проверяем, авторизован ли клиент по СМС-паролю

		$this->checkNeedSmsAuth('/account/changePassport', 'change_passport_data');

		//проверяем, прошел ли клиент идентификацию, прежде чем менять паспортные данные
		$bIsNeedPassportData = Yii::app()->adminKreddyApi->checkIsNeedPassportData();
		if (!$bIsNeedPassportData) //если нет
		{
			//получаем данные для отправки на идентификацию
			$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
			if ($aGetIdent) {
				$oIdentify = new VideoIdentifyForm();
				$oIdentify->setAttributes($aGetIdent);
				$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/changePassport");
				//выводим форму отправки на идентификацию
				$this->render('change_passport_data/need_identify', array('model' => $oIdentify));
				Yii::app()->end();
			}
		} //иначе идем дальше, рисуем форму
		/**
		 * Требование $bIsNeedPassportData создается после прохождения идентификации, т.е. после прохождения
		 * идентификации метод checkIsNeedPassportData() вернет true,
		 * потому когда он возвращает false, мы просто предлагаем пройти идентификацию
		 */

		$oChangePassportForm = new ChangePassportForm();

		$this->changeClientData($oChangePassportForm, 'change_passport_data');

		$this->render('change_passport_data/passport_form', array('oChangePassportForm' => $oChangePassportForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangePassportSendSmsCode()
	{
		Yii::app()->user->getFlash('warning'); //удаляем warning

		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_PASSPORT, 'change_passport_data');
	}

	/**
	 * Проверка СМС-кода для смены паспортных данных
	 */
	public function actionChangePassportCheckSmsCode()
	{
		Yii::app()->user->getFlash('warning'); //удаляем warning

		$oSmsCodeForm = new SMSCodeForm('codeRequired');
		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam('SMSCodeForm');
			$oSmsCodeForm->setAttributes($aPost);
			if ($oSmsCodeForm->validate()) {
				//забираем сохраненные в сессию данные нового паспорта
				$aPassportData = Yii::app()->adminKreddyApi->getPassportData();

				//отправляем данные в API
				$bSuccess = Yii::app()->adminKreddyApi->changePassport($oSmsCodeForm->smsCode, $aPassportData);

				if ($bSuccess) {

					$this->render('change_passport_data/success');
					Yii::app()->end();

				} elseif (!Yii::app()->adminKreddyApi->isSuccessfulLastSmsCode()) {

					$oSmsCodeForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());

				} else {

					Yii::app()->user->setFlash('error', 'Невозможно изменить паспортные данные. Возможно, такие паспортные данные уже присутствуют в системе Кредди.');

					$oChangePassportForm = new ChangePassportForm();
					$oChangePassportForm->setAttributes($aPassportData);

					$this->render('change_passport_data/passport_form', array('oChangePassportForm' => $oChangePassportForm));
					Yii::app()->end();

				}
			}
		}

		$this->render('change_passport_data/check_sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
	}

	/**
	 * Смена цифрового кода, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangeNumericCode()
	{
		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changeNumericCode', 'change_numeric_code');

		$oChangeNumericCodeForm = new ChangeNumericCodeForm();

		$this->changeClientData($oChangeNumericCodeForm, 'change_numeric_code');

		$this->render('change_numeric_code/numeric_code_form', array('oChangeNumericCodeForm' => $oChangeNumericCodeForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangeNumericCodeSendSmsCode()
	{
		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_NUMERIC_CODE, 'change_numeric_code');
	}

	/**
	 * Проверка СМС-кода для смены цифрового кода
	 */
	public function actionChangeNumericCodeCheckSmsCode()
	{
		$oChangeNumericCodeForm = new ChangeNumericCodeForm();

		$this->changeClientDataCheckSmsCode($oChangeNumericCodeForm, AdminKreddyApiComponent::API_ACTION_CHANGE_NUMERIC_CODE, 'change_numeric_code');
	}

	/**
	 * Смена секретного вопроса, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangeSecretQuestion()
	{

		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changeSecretQuestion', 'change_secret_question');

		$oChangeSecretQuestionForm = new ChangeSecretQuestionForm();

		$this->changeClientData($oChangeSecretQuestionForm, 'change_secret_question');

		$this->render('change_secret_question/secret_question_form', array('oChangeSecretQuestionForm' => $oChangeSecretQuestionForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangeSecretQuestionSendSmsCode()
	{
		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_SECRET_QUESTION, 'change_secret_question');
	}

	/**
	 * Проверка СМС-кода для смены паспортных данных
	 */
	public function actionChangeSecretQuestionCheckSmsCode()
	{
		$oChangeSecretQuestionForm = new ChangeSecretQuestionForm();

		$this->changeClientDataCheckSmsCode($oChangeSecretQuestionForm, AdminKreddyApiComponent::API_ACTION_CHANGE_SECRET_QUESTION, 'change_secret_question');
	}

	/**
	 * Смена настройки двухфакторной аутентификации, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangeSmsAuthSetting()
	{

		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changeSmsAuthSetting', 'change_sms_auth_setting');

		$oChangeSmsAuthSettingForm = new ChangeSmsAuthSettingForm();

		$this->changeClientData($oChangeSmsAuthSettingForm, 'change_sms_auth_setting');

		if ($aClientInfo = Yii::app()->adminKreddyApi->getClientInfo()) {
			$oChangeSmsAuthSettingForm->sms_auth_enabled = $aClientInfo['client_data']['sms_auth_enabled'];
		}
		$this->render('change_sms_auth_setting/sms_auth_setting_form', array('oChangeSmsAuthSettingForm' => $oChangeSmsAuthSettingForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangeSmsAuthSettingSendSmsCode()
	{
		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_SMS_AUTH_SETTING, 'change_sms_auth_setting');
	}

	/**
	 * Проверка СМС-кода для смены настройки двухфакторной аутентификации
	 */
	public function actionChangeSmsAuthSettingCheckSmsCode()
	{

		$oChangeSmsAuthSettingForm = new ChangeSmsAuthSettingForm();


		$this->changeClientDataCheckSmsCode($oChangeSmsAuthSettingForm, AdminKreddyApiComponent::API_ACTION_CHANGE_SMS_AUTH_SETTING, 'change_sms_auth_setting');

	}

	/**
	 * Смена пароля, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangePassword()
	{
		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changePassword', 'change_password');

		$oChangePasswordForm = new ChangePasswordForm();

		$this->changeClientData($oChangePasswordForm, 'change_password');

		$this->render('change_password/password_form', array('oChangePasswordForm' => $oChangePasswordForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangePasswordSendSmsCode()
	{
		$oChangePasswordForm = new ChangePasswordForm();
		$aData = Yii::app()->adminKreddyApi->getClientChangeData($oChangePasswordForm);

		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_PASSWORD, 'change_password', $aData);
	}

	/**
	 * Проверка СМС-кода для смены пароля
	 */
	public function actionChangePasswordCheckSmsCode()
	{

		$oChangePasswordForm = new ChangePasswordForm();

		$this->changeClientDataCheckSmsCode($oChangePasswordForm, AdminKreddyApiComponent::API_ACTION_CHANGE_PASSWORD, 'change_password');
	}

	/**
	 * Смена пароля, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangeEmail()
	{
		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changeEmail', 'change_email');

		$oChangeEmailForm = new ChangeEmailForm();

		$this->changeClientData($oChangeEmailForm, 'change_email');

		$this->render('change_email/email_form', array('oChangeEmailForm' => $oChangeEmailForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangeEmailSendSmsCode()
	{
		$oChangeEmailForm = new ChangeEmailForm();
		$aData = Yii::app()->adminKreddyApi->getClientChangeData($oChangeEmailForm);

		$this->changeClientDataSendSmsCode(AdminKreddyApiComponent::API_ACTION_CHANGE_EMAIL, 'change_email', $aData);
	}

	/**
	 * Проверка СМС-кода для смены цифрового кода
	 */
	public function actionChangeEmailCheckSmsCode()
	{

		$oChangeEmailForm = new ChangeEmailForm();

		$this->changeClientDataCheckSmsCode($oChangeEmailForm, AdminKreddyApiComponent::API_ACTION_CHANGE_EMAIL, 'change_email');
	}


	/**
	 * Экшен получает эвент нажатия кнопки видеоидентификации
	 */
	public function actionGoIdentify()
	{
		//ставим флаг "клиент ушел на идентификацию"
		Yii::app()->adminKreddyApi->setClientOnIdentify(true);
		Yii::app()->end();
	}

	/**
	 * Вывод формы выбора продукта для подписки
	 */
	public function actionSubscribe()
	{
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account/subscribe'));

		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			// если невозможно - выводим сообщение о недоступности
			$this->render('subscription/index', array('sView' => 'subscribe_not_available', 'oModel' => null));
			Yii::app()->end();
		}

		//проверяем ответ checkSubscribe, не нужно ли привязать карту
		if (Yii::app()->adminKreddyApi->checkSubscribeNeedCard()) {
			$this->render('subscription/index', array('sView' => 'need_card', 'oModel' => null));
			Yii::app()->end();
		}

		//выбираем представление в зависимости от статуса СМС-авторизации
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {

			//проверяем, нужна ли повторная видеоидентификация
			if (Yii::app()->adminKreddyApi->checkIsNeedIdentify()) {
				$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
				if ($aGetIdent) {
					$oIdentify = new VideoIdentifyForm();
					$oIdentify->setAttributes($aGetIdent);
					$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/subscribe");
					//выводим форму отправки на идентификацию
					$this->render('subscription/index', array('sView' => 'need_identify', 'oModel' => $oIdentify));
					Yii::app()->end();
				}
			} elseif (Yii::app()->adminKreddyApi->checkIsNeedPassportData()) {
				Yii::app()->user->setFlash('warning', Yii::app()->adminKreddyApi->formatMessage(AdminKreddyApiComponent::C_NEED_PASSPORT_DATA));

				$this->redirect('/account/changePassport');
			}

			//удаляем сохраненные при регистрации данные продукта
			//делается для того, чтобы в случае перехода на "ручной" выбор продукта после регистрации
			//клиент смог выбрать новый продукт и оформить именно его, а не выбранный ранее
			//(сделано дополнительно к приоритету POST-запроса в doSubscribe)
			Yii::app()->user->setState('new_client', null);
			Yii::app()->user->setState('product', null);
			Yii::app()->user->setState('flex_time', null);
			Yii::app()->user->setState('flex_amount', null);
			Yii::app()->user->setState('channel_id', null);

			$sView = 'subscribe';
		} else {
			$sView = 'subscribe_not_sms_auth';
		}


		$oProductForm = new ClientSubscribeForm();

		$this->render('subscription/index', array('sView' => $sView, 'oModel' => $oProductForm));
	}

	/**
	 * Записывает в сессию пакет, выбранный пользователем, и выводит форму выбора канала
	 */
	public function actionSelectChannel()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account/subscribe'));
		}

		//выбираем нужную модель
		$oProductForm = new ClientSubscribeForm();

		//если есть POST-запрос
		if (Yii::app()->request->getIsPostRequest()) {
			//получаем данные из POST-запроса
			$aPost = Yii::app()->request->getPost(get_class($oProductForm), array());

			$oProductForm->setAttributes($aPost);

			if ($oProductForm->validate()) {
				//сохраняем в сессию выбранный продукт
				Yii::app()->adminKreddyApi->setSubscribeSelectedProduct($oProductForm->product);

				$this->render('subscription/index', array('sView' => 'select_channel', 'oModel' => $oProductForm));
				//$this->render($sView, array('sFormName' => get_class($oProductForm)));
				Yii::app()->end();
			}
		}

		$this->render('subscription/index', array('sView' => 'subscribe', 'oModel' => $oProductForm));
		Yii::app()->end();
	}

	/**
	 * Обработка данных от формы, переданной из /account/subscribe
	 * и вывод формы с требованием подтверждения по СМС (с кнопкой "Отправить смс")
	 *
	 * Сюда возможен редирект сразу после регистрации! В этом случае POST-запрос заменяется сохраненными
	 * в setState данными, и эти данные загружаются в форму
	 */
	public function actionDoSubscribe()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account/subscribe'));
		}

		// устанавливаем returnUrl, чтобы после видеоидентификации пользователь вернулся на эту страницу
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account/doSubscribe'));

		//проверяем, нужна ли повторная видеоидентификация
		if (Yii::app()->adminKreddyApi->checkIsNeedIdentify()) {
			$aGetIdent = Yii::app()->adminKreddyApi->getIdentify();
			if ($aGetIdent) {
				$oIdentify = new VideoIdentifyForm();
				$oIdentify->setAttributes($aGetIdent);
				$oIdentify->redirect_back_url = Yii::app()->createAbsoluteUrl("/account/doSubscribe");
				//выводим форму отправки на идентификацию
				$this->render('subscription/index', array('sView' => 'need_identify', 'oModel' => $oIdentify));
				Yii::app()->end();
			}
		} elseif (Yii::app()->adminKreddyApi->checkIsNeedPassportData()) { //проверяем, нужно ли обновить паспортные данные
			//устанавливаем warning
			Yii::app()->user->setFlash('warning', Yii::app()->adminKreddyApi->formatMessage(AdminKreddyApiComponent::C_NEED_PASSPORT_DATA));
			//отправляем на изменение паспортных данных
			$this->redirect('/account/changePassport');
		}

		//получаем сохраненные при регистрации данные займа (если есть)
		//TODO возможно, делать это только если есть state new_client
		$sProduct = Yii::app()->user->getState('product');
		$sChannelsId = Yii::app()->user->getState('channel_id');
		$iPayType = Yii::app()->user->getState('pay_type');


		//получаем из строкового списка каналов вида "1_2_3" (для мобильных каналов) один ID канала, доступного клиенту, в int формате
		$iChannelId = Yii::app()->adminKreddyApi->getClientSelectedChannelByIdString($sChannelsId);
		if ($iPayType) {
			$iProduct = Yii::app()->productsChannels->getProductBySelectedType($sProduct, $iPayType);
		} else {
			$iProduct = (int)$sProduct;
		}

		$bIsRedirect = false;
		$aData = array();
		//если есть сохраненные данные в getState, то их переносим в массив $aData
		if (!empty($iProduct) && !empty($sChannelsId)) { //для kreddy.ru
			$bIsRedirect = true; //флаг "был произведен редирект с сохранением данных"
			$aData = array('product' => $iProduct, 'channel_id' => $iChannelId);
		}

		/* если был редирект с сохранением данных, но выбранный канал равен 0,
		 * т.е. выбранный канал отсутствовал в списке доступных клиенту
		 * то нужно его отправить на привязку карты, с сообщением об этом
		 */
		if ($bIsRedirect && $iChannelId === 0) {
			Yii::app()->user->setFlash('warning', AdminKreddyApiComponent::C_CARD_NOT_AVAILABLE);
			$this->redirect('/account/addCard'); //после привязки редирект вернет клиента обратно
		}

		//выбираем нужную модель
		$oProductForm = new ClientSubscribeForm('channelRequired');
		//если есть POST-запрос или были данные в getState перед редиректом
		if (Yii::app()->request->getIsPostRequest() || $bIsRedirect) {
			//получаем данные из POST-запроса, либо из массива сохраненных до редиректа данных
			if (Yii::app()->request->getIsPostRequest()) {
				$aPost = Yii::app()->request->getParam(get_class($oProductForm), array());
				$aPost['product'] = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
			} else {
				$aPost = $aData;
			}

			$oProductForm->setAttributes($aPost);

			if ($oProductForm->validate()) {

				//сохраняем в сессию выбранный продукт
				$oForm = new SMSCodeForm('sendRequired');
				Yii::app()->adminKreddyApi->setSubscribeSelectedProduct($oProductForm->product);
				Yii::app()->adminKreddyApi->setSubscribeSelectedChannel($oProductForm->channel_id);

				$this->render('subscription/index', array('sView' => 'do_subscribe', 'oModel' => $oForm));
				Yii::app()->end();
			}
		}
		$this->render('subscription/index', array('sView' => 'subscribe', 'oModel' => $oProductForm));
		Yii::app()->end();
	}

	/**
	 * Обработка данных от /account/doSubscribe
	 * проверяет, была ли нажата кнопка "Отправить" (наличие в POST-запросе значения sendSmSCode=1)
	 * если нет, то редирект на /account/subscribe
	 * если кнопка нажата, отправляет СМС и рисует форму ввода СМС-кода
	 */
	public function actionDoSubscribeSmsConfirm()
	{
		//если действует мораторий
		//или API ответил, что действие невозможно
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oForm = new SMSCodeForm('sendRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());
		$oForm->setAttributes($aPost);
		//проверяем, передан ли параметр sendSmsCode (валидируем по правилам формы, сценарий sendRequired)
		if ($oForm->validate()) {
			if (Yii::app()->adminKreddyApi->sendSmsSubscribe()) {
				unset($oForm);
				//создаем новую форму с новым сценарием валидации - codeRequired
				$oForm = new SMSCodeForm('codeRequired');
				$this->render('subscription/index', array('sView' => 'do_subscribe_check_sms_code', 'oModel' => $oForm));
				Yii::app()->end();
			}
			//рисуем ошибку
			$this->render('subscription/index', array('sView' => 'do_subscribe_error', 'oModel' => $oForm));
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl('/account/subscribe'));

	}

	/**
	 * Проверка СМС-кода для подписки и отправка запроса на подписку в API
	 * если все ОК - сообщаем это клиенту, иначе перерисовываем форму проверки с ошибками
	 */
	public function actionDoSubscribeCheckSmsCode()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		if (!Yii::app()->request->isPostRequest) {
			$this->redirect(Yii::app()->createUrl('/account/subscribe'));
		}

		$oForm = new SMSCodeForm('codeRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());
		$bTriesExceed = false;

		$oForm->setAttributes($aPost);
		//валидируем
		if ($oForm->validate()) {
			$iProduct = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
			$iChannel = Yii::app()->adminKreddyApi->getSubscribeSelectedChannel();
			$iAmount = false;
			$iTime = false;

			if (($iProduct && $iChannel) || ($iProduct && $iAmount && $iChannel && $iTime)) {
				//проверяем, не кончились ли попытки
				//TODO: вынести из сессии в лог
				$bTriesExceed = Yii::app()->adminKreddyApi->getIsSmsCodeTriesExceed();
				//если попытки не кончились, пробуем оформить подписку
				if (!$bTriesExceed) {
					//проверяем точку входа, делаем подписку согласно точке входа
					$bSubscribe = Yii::app()->adminKreddyApi->doSubscribe($oForm->smsCode, $iProduct, $iChannel);

					if ($bSubscribe) {
						//сбрасываем счетчик попыток ввода кода
						Yii::app()->adminKreddyApi->resetSmsCodeTries();

						$this->render('subscription/index', array('sView' => 'subscribe_complete', 'oModel' => null));
						Yii::app()->end();
					}
				} else {
					//устанвливаем сообщение об ошибке
					$oForm->addError('smsCode', Dictionaries::C_ERR_SMS_TRIES);

				}
			} else {
				$oForm->addError('smsCode', AdminKreddyApiComponent::ERROR_MESSAGE_UNKNOWN);
			}


			if (!Yii::app()->adminKreddyApi->getIsNotAllowed() && !Yii::app()->adminKreddyApi->getIsError()) {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
			} else {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastMessage());
			}

			//если попытки ввода кода закончились
			if ($bTriesExceed) {
				//устанвливаем сообщение об ошибке
				$oForm->addError('smsCode', Dictionaries::C_ERR_SMS_TRIES);
			}
		}
		$this->render('subscription/index', array('sView' => 'do_subscribe_check_sms_code', 'oModel' => $oForm));
	}

	/**
	 * Вывод формы выбора займа
	 */
	public function actionLoan()
	{
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account/loan'));

		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkLoan()) {
			// если невозможно - выводим сообщение о недоступности
			$this->render('loan/index', array('sView' => 'loan_not_available', 'oModel' => null));
			Yii::app()->end();
		}

		if (Yii::app()->adminKreddyApi->isSubscriptionAwaitingConfirmationStatus()) {
			$iChannelId = Yii::app()->adminKreddyApi->getSelectedChannelId();
			Yii::app()->adminKreddyApi->setLoanSelectedChannel($iChannelId);
			$oForm = new SMSCodeForm('sendRequired');
			$this->render('loan/index', array('sView' => 'do_loan', 'oModel' => $oForm));
			Yii::app()->end();
		}

		//выбираем представление в зависимости от статуса СМС-авторизации
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {

			$sView = 'loan';
		} else {
			$sView = 'loan_not_sms_auth';
		}

		$oLoanForm = new ClientLoanForm();
		$this->render('loan/index', array('sView' => $sView, 'oModel' => $oLoanForm));

	}

	/**
	 * Обработка данных от формы, переданной из /account/loan
	 * и вывод формы с требованием подтверждения по СМС (с кнопкой "Отправить смс")
	 */
	public function actionDoLoan()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oLoanForm = new ClientLoanForm();
		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam(get_class($oLoanForm), array());

			$oLoanForm->setAttributes($aPost);

			if ($oLoanForm->validate()) {
				//сохраняем в сессию выбранный продукт
				Yii::app()->adminKreddyApi->setLoanSelectedChannel($oLoanForm->channel_id);
				$oForm = new SMSCodeForm('sendRequired');
				$this->render('loan/index', array('sView' => 'do_loan', 'oModel' => $oForm));
				Yii::app()->end();
			}
		}
		$this->render('loan/index', array('sView' => 'loan', 'oModel' => $oLoanForm));
		Yii::app()->end();
	}

	/**
	 * Обработка данных от /account/doLoan
	 * проверяет, была ли нажата кнопка "Отправить" (наличие в POST-запросе значения sendSmSCode=1)
	 * если нет, то редирект на /account/loan
	 * если кнопка нажата, отправляет СМС и рисует форму ввода СМС-кода
	 */
	public function actionDoLoanSmsConfirm()
	{
		//если действует мораторий
		//или API ответил, что действие невозможно
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oForm = new SMSCodeForm('sendRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());
		$oForm->setAttributes($aPost);
		//проверяем, передан ли параметр sendSmsCode (валидируем по правилам формы, сценарий sendRequired)
		if ($oForm->validate()) {
			if (Yii::app()->adminKreddyApi->sendSmsLoan()) {
				unset($oForm);
				//создаем новую форму с новым сценарием валидации - codeRequired
				$oForm = new SMSCodeForm('codeRequired');
				$this->render('loan/index', array('sView' => 'do_loan_check_sms_code', 'oModel' => $oForm));
				Yii::app()->end();
			}
			//рисуем ошибку
			$this->render('loan/index', array('sView' => 'do_loan_error', 'oModel' => $oForm));
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl('/account/loan'));

	}

	/**
	 * Проверка СМС-кода для подписки и отправка запроса на подписку в API
	 * если все ОК - сообщаем это клиенту, иначе перерисовываем форму проверки с ошибками
	 */
	public function actionDoLoanCheckSmsCode()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		if (!Yii::app()->request->isPostRequest) {
			$this->redirect(Yii::app()->createUrl('/account/loan'));
		}

		$oForm = new SMSCodeForm('codeRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());


		$oForm->setAttributes($aPost);
		if ($oForm->validate()) {
			$iChannelId = Yii::app()->adminKreddyApi->getLoanSelectedChannel();
			//получаем массив, содержащий ID продукта и тип канала получения
			//проверяем, что в массиве 2 значения (ID и канал)
			//проверяем, не кончились ли попытки
			$bTriesExceed = Yii::app()->adminKreddyApi->getIsSmsCodeTriesExceed();
			//если попытки не кончились, пробуем оформить заём
			if (!$bTriesExceed) {
				if (Yii::app()->adminKreddyApi->doLoan($oForm->smsCode, $iChannelId)) {
					//сбрасываем счетчик попыток ввода кода
					Yii::app()->adminKreddyApi->resetSmsCodeTries();
					$this->render('loan/index', array('sView' => 'loan_complete', 'oModel' => null));
					Yii::app()->end();
				}
			} else {
				//устанвливаем сообщение об ошибке
				$oForm->addError('smsCode', Dictionaries::C_ERR_SMS_TRIES);
			}

			if (!Yii::app()->adminKreddyApi->getIsNotAllowed() && !Yii::app()->adminKreddyApi->getIsError()) {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
			} else {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastMessage());
			}

		}
		$this->render('loan/index', array('sView' => 'do_loan_check_sms_code', 'oModel' => $oForm));
	}

	public function actionCancelRequest()
	{
		if (!Yii::app()->adminKreddyApi->getIsCanCancelRequest()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		if (Yii::app()->request->getPost('cancel')) {
			if (Yii::app()->adminKreddyApi->doCancelRequest()) {
				Yii::app()->user->setFlash('success', AdminKreddyApiComponent::C_REQUEST_CANCEL_SUCCESS);
				$this->redirect(Yii::app()->createUrl('/account/subscribe'));
			} else {
				Yii::app()->user->setFlash('error', AdminKreddyApiComponent::C_REQUEST_CANCEL_ERROR);
				$this->redirect(Yii::app()->createUrl('/account/cancelRequest'));
			}

		}

		$this->render('cancel_request');
	}

	public function actionSendSmsPass()
	{
		//если уже авторизованы по СМС, то редирект на главную страницу ЛК
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oSmsPassForm = new SMSPasswordForm('sendRequired');
		$aPost = Yii::app()->request->getParam('SMSPasswordForm', array());
		$oSmsPassForm->setAttributes($aPost);

		if ($oSmsPassForm->validate()) {
			if (!Yii::app()->adminKreddyApi->checkSmsPassSent() && !Yii::app()->adminKreddyApi->sendSmsPassword(false)) {
				$this->render('sms_password/send_password_error', array('model' => $oSmsPassForm,));
				Yii::app()->end();
			}
		}
		$this->redirect(Yii::app()->createUrl('/account/smsPassAuth'));
	}

	/**
	 * Авторизация по СМС-паролю
	 */
	public function actionSmsPassAuth()
	{
		//если уже авторизованы по СМС, то редирект на главную страницу ЛК
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}


		$oSmsPassForm = new SMSPasswordForm('passRequired');
		$aPost = Yii::app()->request->getParam('SMSPasswordForm', array());

		if (!Yii::app()->adminKreddyApi->checkSmsPassSent()) { //если СМС не отправлялось
			$this->render('sms_password/send_password', array('model' => $oSmsPassForm,));
			Yii::app()->end();
		}

		if ($aPost) { //если передан POST-запрос, т.е. отправлен СМС-пароль на проверку
			$oSmsPassForm->setAttributes($aPost);
			if ($oSmsPassForm->validate()) {
				//проверяем, не кончились ли попытки
				$bTriesExceed = Yii::app()->adminKreddyApi->getIsSmsPassTriesExceed();
				//если попытки не исчерпаны, проверяем, удалось ли авторизоваться с этим паролем
				if (!$bTriesExceed) {
					if (Yii::app()->adminKreddyApi->getSmsAuth($oSmsPassForm->smsPassword)) {
						//сбрасываем счетчик попыток ввода кода
						Yii::app()->adminKreddyApi->resetSmsPassTries();
						$this->redirect(Yii::app()->user->getReturnUrl());
					} else {
						$oSmsPassForm->addError('smsPassword', Yii::app()->adminKreddyApi->getLastSmsMessage());
					}
				} else {
					//если попытки кончились
					$oSmsPassForm->addError('smsPassword', Dictionaries::C_ERR_SMS_PASS_TRIES);
				}
			}
		}
		$this->render('sms_password/check_password', array('model' => $oSmsPassForm,));
	}

	public function actionSmsPassResend()
	{
		/**
		 * если время до переотправки не больше 0 (т.е. истекло)
		 * но СМС отправить не удалось
		 * то выведем форму с ошибкой отправки, полученной от API
		 */
		if (!(Yii::app()->adminKreddyApi->getSmsPassLeftTime() > 0)
			&& !Yii::app()->adminKreddyApi->sendSmsPassword(true)
		) {
			$oForm = new SMSPasswordForm('sendRequired');
			$oForm->addError('phone', Yii::app()->adminKreddyApi->getLastSmsMessage());
			$this->render('sms_password/send_password', array('model' => $oForm,));
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl('account/smsPassAuth'));
	}

	/**
	 *  Форма восстановления пароля, необходимого для входа в личный кабинет
	 */
	public function actionResetPassword()
	{
		$this->layout = '/layouts/column1';

		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->createUrl("/account"));
		}

		$aPost = Yii::app()->request->getParam('AccountResetPasswordForm', array());
		$oForm = new AccountResetPasswordForm();

		// если передан телефон на проверку
		if ($aPost) {
			$oForm->setAttributes($aPost);
			//проверяем телефон на валидность и если введён новый телефон и не удалось отправить на него SMS, то выдаём соответствующее сообщение
			if ($oForm->validate()) {
				$aData = Yii::app()->adminKreddyApi->getResetPassData();
				if (empty($aData['phone'])) {
					$aData['phone'] = '';
				}
				if ($aData['phone'] !== $oForm->phone
					&& !Yii::app()->adminKreddyApi->resetPasswordSendSms($oForm->getAttributes(), false)
				) {
					$oForm->addError('phone', Yii::app()->adminKreddyApi->getLastSmsMessage());
				} else {
					$this->redirect(Yii::app()->createUrl("/account/resetPassSendPass"));
				}
			}
		}
		$this->render('reset_password/send_code', array('model' => $oForm,));
	}

	/**
	 * Запрос на повторную отправку кода для восстановления пароля
	 */
	public function actionResetPasswordResendSmsCode()
	{
		$this->layout = '/layouts/column1';
		$oForm = new AccountResetPasswordForm();
		//проверяем, есть ли телефон в сессии
		if (Yii::app()->adminKreddyApi->checkResetPassPhone()) {
			//если время до следующей переотправки СМС не истекло
			if (Yii::app()->adminKreddyApi->getResetPassSmsCodeLeftTime() > 0) {
				$this->redirect(Yii::app()->createUrl('account/resetPassSendPass'));
			}
			//загружаем в форму телефон, сохраненный в сессии

			$aData = Yii::app()->adminKreddyApi->getResetPassData();
			$oForm->setAttributes($aData);
			if ($oForm->validate()) {
				//делаем запрос на повторную отправку смс
				if (Yii::app()->adminKreddyApi->resetPasswordSendSms($oForm->getAttributes(), true)) {
					$this->redirect(Yii::app()->createUrl("/account/resetPassSendPass"));
				} else {
					$oForm->addError('phone', Yii::app()->adminKreddyApi->getLastSmsMessage());
					$this->render('reset_password/send_password', array('model' => $oForm,));
					Yii::app()->end();
				}
			}
		}
		$this->redirect(Yii::app()->createUrl("account/resetPassword"));
	}

	/**
	 * Проверка кода, отправленного в SMS. Если код верен - отправка SMS с паролем на телефон из сессии
	 */
	public function actionResetPassSendPass()
	{
		$this->layout = '/layouts/column1';

		// если в сессии нет телефона, то перенаправляем на форму ввода телефона
		if (!Yii::app()->adminKreddyApi->checkResetPassPhone()) {
			$this->redirect(Yii::app()->createUrl("account/resetPassword"));
		}

		$oCodeForm = new AccountResetPasswordForm('codeRequired');
		$aPost = Yii::app()->request->getParam('AccountResetPasswordForm', array());

		if ($aPost) {
			$oCodeForm->setAttributes($aPost);
			$sSmsCode = $oCodeForm->sms_code; //временно сохраняем sms-код
			$aData = Yii::app()->adminKreddyApi->getResetPassData();
			$oCodeForm->setAttributes($aData); //загружаем в модель данные из сессии
			$oCodeForm->sms_code = $sSmsCode; //возвращаем смс-код
			if ($oCodeForm->validate()) {
				$bResult = Yii::app()->adminKreddyApi->resetPasswordCheckSms($oCodeForm->getAttributes());
				//проверяем, удалось ли отправить смс
				if ($bResult) {
					//переходим на страницу "успешно"
					$this->redirect(Yii::app()->createUrl("/account/resetPassSmsSentSuccess"));
				} else {
					//добавляем ошибку перед выводом формы
					$oCodeForm->addError('sms_code', Yii::app()->adminKreddyApi->getLastSmsMessage());
				}
			}
		}
		$this->render('reset_password/send_password', array('model' => $oCodeForm,));
	}

	/**
	 * Сообщение об успешной отправке нового пароля
	 */
	public function actionResetPassSmsSentSuccess()
	{
		$this->layout = '/layouts/column1';

		// если в сессии нет телефона, то перенаправляем на форму ввода телефона
		if (!Yii::app()->adminKreddyApi->checkResetPassPhone()) {
			$this->redirect(Yii::app()->createUrl("account/resetPassword"));
		}

		// очищаем данные
		Yii::app()->adminKreddyApi->clearResetPassSmsCodeState();
		$this->render('reset_password/pass_sent_success');
	}

	public function actionLogin()
	{

		$this->layout = '/layouts/column1';

		if (Yii::app()->user->getIsGuest()) {
			$oModel = new AccountLoginForm;

			// if it is ajax validation request
			if (Yii::app()->request->isAjaxRequest) {
				echo CActiveForm::validate($oModel);
				Yii::app()->end();
			}

			$aPostData = Yii::app()->request->getPost('AccountLoginForm', array());
			$oModel->setAttributes($aPostData);

			if (Yii::app()->request->isPostRequest && $oModel->validate() && $oModel->login()) {
				$this->redirect(Yii::app()->createUrl("/account"));
				Yii::app()->end();
			}
			// display the login form
			$oModel->password = ''; //удаляем пароль из формы, на случай ошибки (чтобы не передавать его в форму)
			$this->render('login', array('model' => $oModel));
		} else {
			if (Yii::app()->user->getState('new_client')) {
				$this->redirect(Yii::app()->createUrl("/account/doSubscribe"));
			}
			$this->redirect(Yii::app()->createUrl("/account"));
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->adminKreddyApi->logout();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Проверяем необходимость СМС-авторизации и рендерим соответствующую форму
	 *
	 * @param $sRedirectUrl
	 * @param $sViewsPath
	 *
	 */
	protected function checkNeedSmsAuth($sRedirectUrl, $sViewsPath)
	{
		if (!Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$oSmsPassForm = new SMSPasswordForm();
			//устанавливаем, куда вернуть клиента после авторизации
			Yii::app()->user->setReturnUrl(Yii::app()->createUrl($sRedirectUrl));
			//рендерим форму запроса СМС-пароля
			$sPassFormRender = $this->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), true, false);
			//рендерим страницу с требованием пройти СМС-авторизацию
			$this->render($sViewsPath . '/need_sms_auth', array('sPassFormRender' => $sPassFormRender));
			Yii::app()->end();
		}
	}

	/**
	 * @param $oChangeForm
	 * @param $sViewsPath
	 */
	protected function changeClientData(ClientFullForm $oChangeForm, $sViewsPath)
	{
		if (Yii::app()->request->isAjaxRequest) {
			echo CActiveForm::validate($oChangeForm);
			Yii::app()->end();
		}

		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam(get_class($oChangeForm));
			$oChangeForm->setAttributes($aPost);
			if ($oChangeForm->validate()) {
				Yii::app()->adminKreddyApi->setClientChangeData($oChangeForm, $aPost);
				$oSmsCodeForm = new SMSCodeForm('sendRequired');
				$this->render($sViewsPath . '/send_sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
				Yii::app()->end();
			}
		}
	}

	/**
	 * @param       $cApiAction
	 * @param       $sViewsPath
	 * @param array $aData
	 */
	protected function changeClientDataSendSmsCode($cApiAction, $sViewsPath, $aData = array())
	{
		$oSmsCodeForm = new SMSCodeForm('sendRequired');
		if (Yii::app()->request->getIsPostRequest()) {

			$aPost = Yii::app()->request->getParam('SMSCodeForm');
			$oSmsCodeForm->setAttributes($aPost);
			if ($oSmsCodeForm->validate()) {
				//запрашиваем СМС-код для подтверждения
				$bSendSms = Yii::app()->adminKreddyApi->sendSmsChangeClientData($cApiAction, $aData);
				if ($bSendSms) { //если СМС отправлено успешно
					unset($oSmsCodeForm);
					$oSmsCodeForm = new SMSCodeForm('codeRequired');
					$this->render($sViewsPath . '/check_sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
				} else {
					$this->render($sViewsPath . '/error', array('oSmsCodeForm' => $oSmsCodeForm));
				}
				Yii::app()->end();
			}
		}
		$this->render($sViewsPath . '/send_sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
	}

	/**
	 * @param ClientFullForm $oChangeForm
	 * @param                $cApiAction
	 * @param                $sViewsPath
	 */
	protected function changeClientDataCheckSmsCode(ClientFullForm $oChangeForm, $cApiAction, $sViewsPath)
	{
		$oSmsCodeForm = new SMSCodeForm('codeRequired');
		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam('SMSCodeForm');
			$oSmsCodeForm->setAttributes($aPost);
			if ($oSmsCodeForm->validate()) {
				//забираем сохраненные в сессию данные о цифровом коде
				$aData = Yii::app()->adminKreddyApi->getClientChangeData($oChangeForm);
				//отправляем данные в API
				$bChangeSecret = Yii::app()->adminKreddyApi->changeClientData($cApiAction, $oSmsCodeForm->smsCode, $aData, get_class($oChangeForm));
				if ($bChangeSecret) { //если нет ошибок
					$this->render($sViewsPath . '/success');
					Yii::app()->end();
				} else {
					$oSmsCodeForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
				}
			}
		}

		$this->render($sViewsPath . '/check_sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
	}
}
