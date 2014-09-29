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
				'actions' => array('login', 'redirect', 'resetPassword', 'resetPassSendPass', 'resetPassSmsSentSuccess', 'resetPasswordResendSmsCode'),
				'users'   => array('*'),
			),
			array(
				'allow',
				'actions' => array(
					'logout', 'index', 'history', 'identify', 'identifySite', 'identifyPhoto', 'identifyApp', 'smsPassAuth',
					'subscribe', 'doSubscribe',	'doSubscribeConfirm',
					'loan', 'doLoan', 'doLoanConfirm', 'cancelLoan',
					'addCard', 'verifyCard', 'successCard', 'refresh',
					'changePassport', 'changePassportSendSmsCode',
					'changeEmail', 'changeEmailSendSmsCode',
					'changeNumericCode', 'changeNumericCodeSendSmsCode',
					'changeSecretQuestion', 'changeSecretQuestionSendSmsCode',
					'changeSmsAuthSetting', 'changeSmsAuthSettingSendSmsCode',
					'changeAutoDebitingSetting', 'changeAutoDebitingSettingSendSmsCode',
					'changePassword', 'changePasswordSendSmsCode',
					'cancelRequest',
					'returnFrom3DSecurity',
					'continueForm', 'loanComplete',
					'goIdentify',
					'takeLoan', 'takeLoanCheckSmsCode',
					'getDocument', 'getDocumentList',
					'PaymentSchedule',
					'pay', 'paySmsCode'
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
			'redirect',
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
			'redirect',
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
				parent::beforeAction($aAction);
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
		$oSmsPassForm = new SMSCodeForm('sendRequired');
		$sPassFormRender = $this->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), true);
		//$sClientInfoRender = $this->renderPartial($sClientInfoView, array(), true);

		$bIsPossibleDoLoan = Yii::app()->adminKreddyApi->checkLoan();
		$bIsNeedSubscriptionConfirm = Yii::app()->adminKreddyApi->isSubscriptionAwaitingConfirmationStatus();
		$bIsNeedSubscriptionPay = Yii::app()->adminKreddyApi->getClientStatus() == AdminKreddyApiComponent::C_SUBSCRIPTION_PAYMENT;
		$bIsNeedLoanConfirm = Yii::app()->adminKreddyApi->checkConfirmLoan();

		$this->render($sIndexView, array(
				'sClientInfoView'            => $sClientInfoView,
				'sPassFormRender'            => $sPassFormRender,
				'sIdentifyRender'            => $sIdentifyRender,
				'bIsPossibleDoLoan'          => $bIsPossibleDoLoan,
				'bIsNeedSubscriptionConfirm' => $bIsNeedSubscriptionConfirm,
				'bIsNeedLoanConfirm'         => $bIsNeedLoanConfirm,
				'bIsNeedSubscriptionPay'     => $bIsNeedSubscriptionPay,
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
			 Попробуй повторить через несколько минут.';
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
		$oSmsPassForm = new SMSCodeForm('sendRequired');
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
			$oChangeAutoDebitingSettingForm = new ChangeAutoDebitingSettingForm();
			$oChangeAutoDebitingSettingForm->flag_enable_auto_debiting = Yii::app()->adminKreddyApi->isAutoDebitingEnabled();

			$this->render('card/success', array(
				'sMessage' => AdminKreddyApiComponent::C_CARD_SUCCESSFULLY_VERIFIED,
				'oChangeAutoDebitingSettingForm' => $oChangeAutoDebitingSettingForm,
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

		$oChangePassportForm = new ChangePassportForm();
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangePassportForm);
		$oChangePassportForm->setAttributes($aData);

		$oForm = new SMSCodeForm('sendRequired');
		$sSmsState = $this->doProcessSmsCode($oForm, SmsCodeComponent::C_TYPE_CHANGE_PASSPORT, array(get_class($oChangePassportForm) => $aData));

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_NEED_SEND:
				$this->render('change_passport_data/sms_code', array('oSmsCodeForm' => $oForm));
				break;
			case SmsCodeComponent::C_STATE_ERROR:
				if (!Yii::app()->adminKreddyApi->isSuccessfulLastSmsCode()) {
					$this->render('change_passport_data/sms_code', array('oSmsCodeForm' => $oForm));
				} else {
					Yii::app()->user->setFlash('error', 'Невозможно изменить паспортные данные. Возможно, такие паспортные данные уже присутствуют в системе Кредди.');

					$this->render('change_passport_data/passport_form', array('oChangePassportForm' => $oChangePassportForm));
				}
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				$this->render('change_passport_data/success');
				break;

			default:
				$this->redirect(Yii::app()->createUrl('/account/loan'));
		}

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
		$oChangeNumericCodeForm = new ChangeNumericCodeForm();
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangeNumericCodeForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_NUMERIC_CODE, 'change_numeric_code', $aData, get_class($oChangeNumericCodeForm));
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
		$oChangeSecretQuestionForm = new ChangeSecretQuestionForm();
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangeSecretQuestionForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_SECRET_QUESTION, 'change_secret_question', $aData, get_class($oChangeSecretQuestionForm));
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
		$oChangeSmsAuthSettingForm = new ChangeSmsAuthSettingForm();
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangeSmsAuthSettingForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_SMS_AUTH_SETTING, 'change_sms_auth_setting', $aData, get_class($oChangeSmsAuthSettingForm));
	}

	/**
	 * Смена настройки двухфакторной аутентификации, выводим форму и проверяем введенные данные если есть POST-запрос
	 */
	public function actionChangeAutoDebitingSetting()
	{
		//проверяем, авторизован ли клиент по СМС-паролю
		$this->checkNeedSmsAuth('/account/changeAutoDebitingSetting', 'change_auto_debiting_setting');

		$oChangeAutoDebitingSettingForm = new ChangeAutoDebitingSettingForm();

		$this->changeClientData($oChangeAutoDebitingSettingForm, 'change_auto_debiting_setting');

		if ($aClientInfo = Yii::app()->adminKreddyApi->getClientInfo()) {
			$oChangeAutoDebitingSettingForm->flag_enable_auto_debiting = Yii::app()->adminKreddyApi->isAutoDebitingEnabled();
		}
		$this->render('change_auto_debiting_setting/auto_debiting_setting_form', array('oChangeAutoDebitingSettingForm' => $oChangeAutoDebitingSettingForm));
	}

	/**
	 * Отправка СМС-кода подтверждения
	 */
	public function actionChangeAutoDebitingSettingSendSmsCode()
	{

		$oChangeAutoDebitingSettingForm = new ChangeAutoDebitingSettingForm();
		$aData = Yii::app()->adminKreddyApi->getClientChangeData($oChangeAutoDebitingSettingForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_AUTO_DEBITING_SETTING, 'change_auto_debiting_setting', $aData, get_class($oChangeAutoDebitingSettingForm));

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
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangePasswordForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_PASSWORD, 'change_password', $aData);
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
		$aData = Yii::app()->adminKreddyApi->getClientData($oChangeEmailForm);

		$this->changeClientDataSmsCode(SmsCodeComponent::C_TYPE_CHANGE_EMAIL, 'change_email', $aData, get_class($oChangeEmailForm));
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

		//проверяем, не нужно ли привязать карту
		if (Yii::app()->adminKreddyApi->checkSubscribeNeedCard()) {
			$this->render('subscription/index', array('sView' => 'need_card', 'oModel' => null));
			Yii::app()->end();
		}

		$oProductForm = new ClientSubscribeForm();

		//если не авторизован по СМС-авторизации
		if (!Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$this->render('subscription/index', array('sView' => 'subscribe_not_sms_auth', 'oModel' => $oProductForm));
			Yii::app()->end();
		}

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

		$this->render('subscription/index', array('sView' => 'subscribe', 'oModel' => $oProductForm));
	}

	/**
	 * Обработка данных от формы, переданной из /account/subscribe
	 *
	 * Сюда возможен редирект сразу после регистрации! В этом случае POST-запрос заменяется сохраненными
	 * в setState данными, и эти данные загружаются в форму
	 */
	public function actionDoSubscribe()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
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

		$oProductForm = new ClientSubscribeForm('allValidate');
		$aPost = Yii::app()->request->getPost(get_class($oProductForm));
		if (!empty($aPost)) {
			$oProductForm->setAttributes($aPost);
			$oProductForm->setProductByAttributes();
			//сохраняем в сессию выбранный продукт
			if ($oProductForm->validate()) {
				Yii::app()->adminKreddyApi->setSubscribeSelectedProduct($oProductForm->product);
			}
		}

		//Если клиент нажал кнопку "Продолжить"
		$iProduct = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
		if ($iProduct && Yii::app()->request->getPost('subscribe_accept')) {

			$bSubscribe = Yii::app()->adminKreddyApi->doSubscribe($iProduct);

			if ($bSubscribe) {
				$this->render('subscription/index', array('sView' => 'subscribe_complete', 'oModel' => null));
				Yii::app()->end();
			}
		}

		//Отображение выбранных параметров и кнопка "продолжить"
		if ($oProductForm->validate()) {
			$this->render('subscription/index', array('sView' => 'do_subscribe', 'oModel' => null));
			Yii::app()->end();
		}

		$this->render('subscription/index', array('sView' => 'subscribe', 'oModel' => $oProductForm));
	}

	/**
	 * Обработка данных от /account/doSubscribe
	 *
	 * Клиент подтверждает создание подписки
	 * Для пост-оплатных проктов
	 * (для предоплатных продуктов фактом подтверждения является оплата)
	 */
	public function actionDoSubscribeConfirm()
	{
		$iProductId = Yii::app()->adminKreddyApi->getSubscriptionProductId();

		if (!$iProductId || Yii::app()->adminKreddyApi->getClientStatus() != AdminKreddyApiComponent::C_SUBSCRIPTION_AWAITING_CONFIRMATION) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oForm = new SMSCodeForm('sendRequired');

		$sSmsState = $this->doProcessSmsCode($oForm, SmsCodeComponent::C_TYPE_SUBSCRIBE);

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_SEND:
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_ERROR:
				$oProductForm = new ClientSubscribeForm();
				$oProductForm->product = Yii::app()->adminKreddyApi->getSubscriptionProductId();
				if ($oProductForm->validate()) {
					$this->render('subscription/index', array('sView' => 'do_subscribe_confirm', 'oModel' => $oForm));
				}
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				$this->redirect(Yii::app()->createUrl('/account/'));
				break;

			default:
				$this->redirect(Yii::app()->createUrl('/account/subscribe'));
		}

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
	 *
	 * Отображается информация о переводе денег, обработка подтверждения на создание запроса на займ
	 */
	public function actionDoLoan()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkLoan() && !Yii::app()->adminKreddyApi->checkConfirmLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oLoanForm = new ClientLoanForm();
		$aPost = Yii::app()->request->getParam(get_class($oLoanForm), array());
		if ($aPost) {
			$oLoanForm->setAttributes($aPost);
			Yii::app()->adminKreddyApi->setLoanSelectedChannel($oLoanForm->channel_id);
		}

		if (Yii::app()->request->getPost('loan_accept')) {
			$iChannel = Yii::app()->adminKreddyApi->getLoanSelectedChannel();
			$bLoanCreated = Yii::app()->adminKreddyApi->doLoan($iChannel);

			if ($bLoanCreated) {
				$this->redirect(Yii::app()->createUrl('/account/doLoanConfirm'));
				Yii::app()->end();
			}
		}

		if ($oLoanForm->validate()) {
			$this->render('loan/index', array('sView' => 'do_loan', 'oModel' => null));
			Yii::app()->end();
		}

		$this->render('loan/index', array('sView' => 'loan', 'oModel' => $oLoanForm));
		Yii::app()->end();
	}

	/**
	 * Обработка данных от /account/doLoan
	 *
	 * Подтверждение запроса на займ (индивидуальных условий)
	 */
	public function actionDoLoanConfirm()
	{
		//если действует мораторий
		//или API ответил, что действие невозможно
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkConfirmLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oForm = new SMSCodeForm('sendRequired');
		$sSmsState = $this->doProcessSmsCode($oForm, SmsCodeComponent::C_TYPE_LOAN);

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_SEND:
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_ERROR:
				$this->render('loan/index', array('sView' => 'do_loan_confirm', 'oModel' => $oForm));
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				$this->redirect(Yii::app()->createUrl('/account/loanComplete'));
				break;

			default:
				$this->redirect(Yii::app()->createUrl('/account/loan'));
		}

	}

	/**
	 * Отмена запроса на займ (индивидуальных условий)
	 */
	public function actionCancelLoan()
	{

		if (!Yii::app()->adminKreddyApi->checkConfirmLoan()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}
		$bResult = Yii::app()->adminKreddyApi->doCancelLoanRequest();
		if ($bResult) {
			Yii::app()->user->setFlash('success', AdminKreddyApiComponent::C_LOAN_REQUEST_CANCEL_SUCCESS);
			$this->redirect(Yii::app()->createUrl('/account'));
		} else {
			Yii::app()->user->setFlash('error', AdminKreddyApiComponent::C_LOAN_REQUEST_CANCEL_ERROR);
			$this->redirect(Yii::app()->createUrl('/account'));
		}

	}

	/**
	 * Сообщение об успешной выдаче займа
	 */
	public function actionLoanComplete()
	{
		$this->render('loan/index', array('sView' => 'loan_complete', 'oModel' => null));
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

	public function actionPay()
	{
		$this->checkNeedSmsAuth('/account/pay', 'pay');

		$oPayForm = new PayForm();

		if (Yii::app()->request->isAjaxRequest) {
			echo CActiveForm::validate($oPayForm);
			Yii::app()->end();
		}

		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam(get_class($oPayForm));
			$oPayForm->setAttributes($aPost);
			if ($oPayForm->validate()) {
				Yii::app()->adminKreddyApi->setClientData($oPayForm, $aPost);
				$oSmsCodeForm = new SMSCodeForm('sendRequired');
				$this->render('pay/sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
				Yii::app()->end();
			}
		} else {
			//обнуляем все данные, если не было пост-запроса
			Yii::app()->smsCode->setResetSmsCodeSentAndTime();
			Yii::app()->smsCode->resetSmsCodeTries();
			Yii::app()->smsCode->setState(SmsCodeComponent::C_STATE_NEED_SEND, SmsCodeComponent::C_TYPE_PAY);
			Yii::app()->adminKreddyApi->setClientData($oPayForm, []);
		}

		$this->render('pay/pay_form', array('oPayForm' => $oPayForm));
	}

	public function actionPaySmsCode()
	{
		Yii::app()->user->getFlash('warning'); //удаляем warning

		$oPayForm = new PayForm();
		$aData = Yii::app()->adminKreddyApi->getClientData($oPayForm);

		if (empty($aData)) {
			$this->redirect(Yii::app()->createUrl('/account/pay'));
		}

		$oPayForm->setAttributes($aData);

		$oForm = new SMSCodeForm('sendRequired');
		$sSmsState = $this->doProcessSmsCode($oForm, SmsCodeComponent::C_TYPE_PAY, array('pay' => $aData));

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_NEED_SEND:
				$this->render('pay/sms_code', array('oSmsCodeForm' => $oForm));
				break;
			case SmsCodeComponent::C_STATE_ERROR:
				if (!Yii::app()->adminKreddyApi->isSuccessfulLastSmsCode()) {
					//ошибка в СМС-коде
					$this->render('pay/sms_code', array('oSmsCodeForm' => $oForm));
				} else {
					//СМС-код верен, но что-то пошло не так
					$sError = Yii::app()->adminKreddyApi->getBankCardPaymentErrorMessage();
					if (!empty($sError)) {
						Yii::app()->user->setFlash('error', $sError);
					}

					$this->redirect(Yii::app()->createUrl('/account/pay'));
				}
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				Yii::app()->adminKreddyApi->setClientData($oPayForm, []);
				$this->render('pay/success');
				break;

			default:
				Yii::app()->smsCode->setResetSmsCodeSentAndTime();
				Yii::app()->smsCode->resetSmsCodeTries();
				Yii::app()->smsCode->setState(SmsCodeComponent::C_STATE_NEED_SEND, SmsCodeComponent::C_TYPE_PAY);
				//Yii::app()->adminKreddyApi->setClientData($oPayForm, []);
				$this->redirect(Yii::app()->createUrl('/account/pay'));
		}

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

		$oForm = new SMSCodeForm('sendRequired');
		$sSmsState = $this->doProcessSmsCode($oForm, SmsCodeComponent::C_TYPE_SITE_AUTH);

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_SEND:
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_ERROR:
				$this->render('sms_password/send_password', array('model' => $oForm));
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				Yii::app()->adminKreddyApi->setSmsAuthDone(true);
				$this->redirect(Yii::app()->user->getReturnUrl());
				break;

			default:
				$this->render('sms_password/send_password', array('model' => $oForm));
		}
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
					Yii::app()->smsCode->setResetSmsCodeSentAndTime();
					Yii::app()->adminKreddyApi->setResetPassData($oForm->getAttributes());
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
			if (Yii::app()->smsCode->getResetSmsCodeLeftTime() > 0) {
				$this->redirect(Yii::app()->createUrl('account/resetPassSendPass'));
			}
			//загружаем в форму телефон, сохраненный в сессии

			$aData = Yii::app()->adminKreddyApi->getResetPassData();
			$oForm->setAttributes($aData);
			if ($oForm->validate()) {
				//делаем запрос на повторную отправку смс
				if (Yii::app()->adminKreddyApi->resetPasswordSendSms($oForm->getAttributes(), true)) {
					Yii::app()->smsCode->setResetSmsCodeSentAndTime();
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
		Yii::app()->smsCode->clearResetSmsCodeState();
		$this->render('reset_password/pass_sent_success');
	}

	public function actionLogin()
	{

		$this->layout = '/layouts/column1';

		$sReturnUrl = Yii::app()->user->getReturnUrl("/account");

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
				$this->redirect(Yii::app()->createUrl($sReturnUrl));
				Yii::app()->end();
			}
			// display the login form
			$oModel->password = ''; //удаляем пароль из формы, на случай ошибки (чтобы не передавать его в форму)
			$this->render('login', array('model' => $oModel));
		} else {
			if (Yii::app()->user->getState('new_client')) {
				$this->redirect(Yii::app()->createUrl("/account/doSubscribe"));
			}

			$this->redirect(Yii::app()->createUrl($sReturnUrl));
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->smsCode->clearResetSmsCodeState();
		Yii::app()->adminKreddyApi->clearSmsAuthState();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Экшн, осуществляющий логин и редирект клиента по специальному токену, созданному при регистрации в Tornado Api
	 */
	public function actionRedirect()
	{
		// получаем данные
		$sClientCryptData = Yii::app()->request->getParam('client');
		$aClientData = CryptArray::decrypt($sClientCryptData);

		$iClientId = 0;
		$sClientPhone = '';

		try {
			list($sClientId, $sClientPhone) = $aClientData;
			$iClientId = (int)$sClientId;
		} catch (Exception $e) {

			Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
			$this->redirect(Yii::app()->createUrl('/account/doSubscribe'));

		}

		$oClient = ClientData::model()->findByPk($iClientId);

		if ($oClient && $oClient->phone == $sClientPhone && $oClient->api_token) {
			// логинимся с использованием токена из БД, это токен siteApi
			$bLoginSuccess = Yii::app()->adminKreddyApi->loginWithToken($oClient->api_token);

			if ($bLoginSuccess) {
				//автоматический логин юзера в личный кабинет
				$oLogin = new AutoLoginForm(); //модель для автоматического логина в систему
				$oLogin->setAttributes(array('username' => $oClient->phone)); //устанавливаем аттрибуты логина
				if ($oLogin->validate() && $oLogin->login()) {
					//сохраняем данные перед редиректом в ЛК
					$aClientData = $oClient->getAttributes();
					Yii::app()->clientForm->saveDataBeforeRedirectToAccount($aClientData);

					//установим информацию о завершенной регистрации перед редиректом
					Yii::app()->clientForm->setRegisterComplete();
					Yii::app()->clientForm->clearClientSession();

					// удаляем токен из БД
					$oClient->api_token = '***********';
					$oClient->save();
				}
			}
		}

		// при любом результате логина редиректим клиента на подписку
		$this->redirect(Yii::app()->createUrl('/account/doSubscribe'));
	}

	/**
	 * График платежей
	 */
	public function actionPaymentSchedule()
	{
		if (!Yii::app()->adminKreddyApi->isSubscriptionActive()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$aPaymentData = Yii::app()->adminKreddyApi->getCurrentClientProduct();

		if ($aPaymentData['subscription_balance'] > 0) {
			$aPaymentData['subscription_balance'] = 0;
		}

		if ($aPaymentData['loan_balance'] > 0) {
			$aPaymentData['loan_balance'] = 0;
		}

		if ($aPaymentData['balance'] > 0) {
			$aPaymentData['balance'] = 0;
		}

		$aPaymentData['loan_amount'] = Yii::app()->adminKreddyApi->getSubscriptionLoanAmount();

		$aPaymentData = array_map(function ($mValue) {
			if (is_int($mValue)) {
				$mValue = abs($mValue);
			}

			return $mValue;
		}, $aPaymentData);

		$this->render('payment_schedule', ['aPaymentData' => $aPaymentData]);

	}

	/**
	 * Получить документ по id
	 *
	 * @param $id
	 * @param $download
	 *
	 * @throws CHttpException
	 */
	public function actionGetDocument($id, $download = 0)
	{
		// чистим данные от опасных символов
		$id = str_replace(array('.', '/', '\\'), '', $id);

		$sFileName = $id . '.pdf';

		$sFilePath = Yii::app()->document->getFilePath($sFileName);

		if (!file_exists($sFilePath)) {
			//Получаем инфомацию из админки по ИУ
			$aConditionInfo = Yii::app()->adminKreddyApi->getIndividualConditionInfo($id);

			//Если есть ошибка
			if (Yii::app()->adminKreddyApi->getLastCode() != AdminKreddyApiComponent::ERROR_NONE) {
				throw new CHttpException('404');
			}

			//Генерируем документ
			Yii::app()->document->generatePDF('individual_condition_pdf', array('aConditionInfo' => $aConditionInfo));

			//Сохраняем документ в файл
			Yii::app()->document->savePDFToFile($sFileName);
		}


		header('Content-type: application/pdf');
		if ($download) {
			header('Content-Disposition: attachment; filename="Индивидуальные условия.pdf"');
		} else {
			header('Content-disposition: filename="Индивидуальные условия.pdf"');
		}
		echo file_get_contents($sFilePath);

	}

	/**
	 * Получить список документов
	 */
	public function actionGetDocumentList()
	{
		$aConditions = Yii::app()->adminKreddyApi->getIndividualConditionList();

		$this->render('individual_conditions', array('aConditions' => $aConditions));
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
			$oSmsPassForm = new SMSCodeForm();
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
				Yii::app()->adminKreddyApi->setClientData($oChangeForm, $aPost);
				$oSmsCodeForm = new SMSCodeForm('sendRequired');
				$this->render($sViewsPath . '/sms_code', array('oSmsCodeForm' => $oSmsCodeForm));
				Yii::app()->end();
			}
		}
	}

	/**
	 * @param       $sType
	 * @param       $sViewsPath
	 * @param array $aData
	 * @param null  $sFormName
	 */
	protected function changeClientDataSmsCode($sType, $sViewsPath, $aData = array(), $sFormName = null)
	{
		if ($sFormName) {
			$aData = array($sFormName => $aData);
		}

		$oForm = new SMSCodeForm('sendRequired');
		$sSmsState = $this->doProcessSmsCode($oForm, $sType, $aData);

		switch ($sSmsState) {
			case SmsCodeComponent::C_STATE_NEED_CHECK:
			case SmsCodeComponent::C_STATE_NEED_SEND:
			case SmsCodeComponent::C_STATE_ERROR:
				$this->render($sViewsPath . '/sms_code', array('oSmsCodeForm' => $oForm));
				break;
			case SmsCodeComponent::C_STATE_NEED_CHECK_OK:
				$this->render($sViewsPath . '/success');
				break;

			default:
				$this->redirect(Yii::app()->createUrl('/account/loan'));
		}
	}

	/**
	 * @param SMSCodeForm $oForm
	 * @param             $sType
	 * @param array       $aData
	 *
	 * @return string
	 */
	protected function doProcessSmsCode(SMSCodeForm &$oForm, $sType, $aData = array())
	{

		$sResult = $this->getSmsCodeResult($oForm, $sType, $aData);

		Yii::app()->smsCode->setState($sResult, $sType);

		return $sResult;
	}

	/**
	 * Отвечает за функционал и обработку СМС-кодов
	 *
	 * @param SMSCodeForm $oForm
	 * @param             $sType
	 * @param array       $aData
	 *
	 * @return string
	 */
	protected function getSmsCodeResult(SMSCodeForm &$oForm, $sType, $aData = array())
	{
		$aPost = Yii::app()->request->getParam(get_class($oForm), array());

		if (empty($aPost) && Yii::app()->smsCode->getState($sType) == SmsCodeComponent::C_STATE_NEED_SEND) {
			return SmsCodeComponent::C_STATE_NEED_SEND;
		}

		if (!empty($aPost)) {
			$oForm->setAttributes($aPost);
		}

		if ($oForm->validate()) {

			$sAction = SmsCodeComponent::$aApiActions[$sType];

			// Если нужно переотправить СМС код, но еще не прошло время переотправки, то рисуем ошибку
			if ($oForm->sendSmsCode == 1 && $oForm->smsResend == 1 && Yii::app()->smsCode->getResetSmsCodeLeftTime() > 0) {
				$oForm->addError('smsCode', Yii::app()->smsCode->getResendErrorMessage());

				return SmsCodeComponent::C_STATE_ERROR;
			}

			// Отправляем (переотправляем) СМС
			if ($oForm->sendSmsCode == 1 && Yii::app()->adminKreddyApi->doSendSms($sAction, $aData, $oForm->smsResend)) {
				Yii::app()->smsCode->setResetSmsCodeSentAndTime();

				//создаем новую форму с новым сценарием валидации - codeRequired
				return SmsCodeComponent::C_STATE_NEED_CHECK;
			}

			// Если не нужно отправлять (проверка кода)
			if ($oForm->sendSmsCode == 0) {
				//проверяем, не кончились ли попытки
				$bTriesExceed = Yii::app()->smsCode->getIsSmsCodeTriesExceed();

				//если попытки не кончились, пробуем подтвердить действие
				if (!$bTriesExceed) {
					//проверяем точку входа, делаем подписку согласно точке входа
					$bResult = Yii::app()->adminKreddyApi->doCheckSms($sAction, $oForm->smsCode, $aData);

					if ($bResult) {
						//сбрасываем счетчик попыток ввода кода
						Yii::app()->smsCode->setResetSmsCodeSentAndTime();
						Yii::app()->smsCode->resetSmsCodeTries();
						Yii::app()->smsCode->setState(SmsCodeComponent::C_STATE_NEED_SEND, $sType);

						return SmsCodeComponent::C_STATE_NEED_CHECK_OK;
					} else {
						$bSmsCodeOk = Yii::app()->adminKreddyApi->isSuccessfulLastSmsCode();
						// если проверка СМС-кода успешно, но при этом пришла ошибка
						if ($bSmsCodeOk) {
							//сбрасываем все данные об отправке СМС
							Yii::app()->smsCode->setResetSmsCodeSentAndTime();
							Yii::app()->smsCode->resetSmsCodeTries();
							Yii::app()->smsCode->setState(SmsCodeComponent::C_STATE_NEED_SEND, $sType);
						}
					}
				} else {
					//устанвливаем сообщение об ошибке
					$oForm->addError('smsCode', Dictionaries::C_ERR_CODE_TRIES);

					return SmsCodeComponent::C_STATE_ERROR;
				}
			}

			if (!Yii::app()->adminKreddyApi->getIsNotAllowed() && !Yii::app()->adminKreddyApi->getIsError()) {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
			} else {
				$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastMessage());
			}

			return SmsCodeComponent::C_STATE_ERROR;
		}

		return SmsCodeComponent::C_STATE_ERROR;
	}

}
