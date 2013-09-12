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
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('login', 'resetPassword', 'resetPassSendPass', 'resetPassSmsSentSuccess', 'resetPasswordResendSmsCode'),
				'users'   => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('logout', 'index', 'history', 'ajaxSendSms', 'checkSmsPass', 'smsPassAuth', 'smsPassResend', 'subscribe', 'doSubscribe', 'doSubscribeCheckSmsCode', 'doSubscribeSmsConfirm'),
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
		if (!in_array($sActionId, $aActionsNotNeedAuth) &&
			!Yii::app()->adminKreddyApi->getIsAuth()
		) {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}

		return parent::beforeAction($aAction);
	}


	/**
	 * Главная страница личного кабинета
	 */
	public function actionIndex()
	{
		Yii::app()->user->setReturnUrl(Yii::app()->createUrl('/account'));

		//выбираем представление в зависимости от статуса СМС-авторизации
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$sView = 'index_is_sms_auth';
		} else {
			$sView = 'index_not_sms_auth';
		}
		/**
		 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
		 */
		$oSmsPassForm = new SMSPasswordForm('sendRequired');
		$sPassFormRender = $this->renderPartial('sms_password/send_password', array('model' => $oSmsPassForm), true);
		$this->render($sView, array('passFormRender' => $sPassFormRender));
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
		$this->render($sView, array('passFormRender' => $sPassFormRender, 'historyProvider' => $oHistoryDataProvider));
	}

	public function actionSubscribe()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oProductForm = new ClientSubscribeForm();
		$this->render('subscription/subscribe', array('model' => $oProductForm));
	}

	public function actionDoSubscribe()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oProductForm = new ClientSubscribeForm();
		if (Yii::app()->request->getIsPostRequest()) {
			$aPost = Yii::app()->request->getParam('ClientSubscribeForm', array());
			$oProductForm->setAttributes($aPost);

			if ($oProductForm->validate()) {
				Yii::app()->adminKreddyApi->setSubscribeSelectedProduct($oProductForm->product);
				$oForm = new SMSCodeForm('sendRequired');
				$this->render('subscription/do_subscribe', array('model' => $oForm));
				Yii::app()->end();
			}
		}
		$this->render('subscription/subscribe', array('model' => $oProductForm));
		Yii::app()->end();
	}

	public function actionDoSubscribeSmsConfirm()
	{
		//если действует мораторий
		//или API ответил, что действие невозможно
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		if (!Yii::app()->request->getIsPostRequest()) {
			$this->redirect(Yii::app()->createUrl('/account/subscribe'));
		}

		$oForm = new SMSCodeForm('sendRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());
		$oForm->setAttributes($aPost);
		//TODO проверка отправлена ли уже СМС, если да, то не валидируем, а рисуем представление
		if ($oForm->validate()) {

			if (Yii::app()->adminKreddyApi->sendSmsSubscribe()) {
				$oForm = new SMSCodeForm('codeRequired');
				$this->render('subscription/do_subscribe_check_sms_code', array('model' => $oForm));
				Yii::app()->end();
			}
			$oForm = new SMSCodeForm('sendRequired');
			$this->render('subscription/do_subscribe_error', array('model' => $oForm));
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl('/account/subscribe'));

	}

	public function actionDoSubscribeCheckSmsCode()
	{
		//проверяем, возможно ли действие
		if (!Yii::app()->adminKreddyApi->checkSubscribe()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oForm = new SMSCodeForm('codeRequired');
		$aPost = Yii::app()->request->getParam('SMSCodeForm', array());
		$oForm->setAttributes($aPost);
		if ($oForm->validate()) {
			$iProduct = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();
			if (Yii::app()->adminKreddyApi->doSubscribe($oForm->smsCode, $iProduct, 'kreddy')) {
				$this->render('subscription/subscribe_complete', array('message' => Yii::app()->adminKreddyApi->getLastMessage()));
				Yii::app()->end();
			}
			$oForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
		}
		$this->render('subscription/do_subscribe_check_sms_code', array('model' => $oForm));
	}


	public function actionSendSmsPass()
	{
		//если уже авторизованы по СМС, то редирект на главную страницу ЛК
		if (Yii::app()->adminKreddyApi->getIsSmsAuth()) {
			$this->redirect(Yii::app()->createUrl('/account'));
		}

		$oSmsPassForm = new SMSPasswordForm('codeRequired');
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

		if ($aPost) { //если передан POST-запрос, т.е. отправлен СМС-пароль на проверку
			$oSmsPassForm->setAttributes($aPost);
			if ($oSmsPassForm->validate()) {
				//проверяем, удалось ли авторизоваться с этим паролем
				if (Yii::app()->adminKreddyApi->getSmsAuth($oSmsPassForm->smsPassword)) {
					$this->redirect(Yii::app()->user->getReturnUrl());
				} else {
					$oSmsPassForm->addError('smsPassword', Yii::app()->adminKreddyApi->getLastSmsMessage());
				}
			}
		} elseif (!Yii::app()->adminKreddyApi->checkSmsPassSent()) { //если СМС не отправлялось
			$this->render('sms_password/send_password', array('model' => $oSmsPassForm,));
			Yii::app()->end();
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

		$oForm->setAttributes($aPost);
		//проверяем телефон на валидность и если введён новый телефон и не удалось отправить на него SMS, то выдаём соответствующее сообщение
		if ($oForm->validate()) {
			if (Yii::app()->adminKreddyApi->getResetPassPhone() !== $oForm->phone
				&& !Yii::app()->adminKreddyApi->resetPasswordSendSms($oForm->phone, false)
			) {
				$oForm->addError('phone', Yii::app()->adminKreddyApi->getLastSmsMessage());
			} else {
				$this->redirect(Yii::app()->createUrl("/account/resetPassSendPass"));
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
			$oForm->phone = Yii::app()->adminKreddyApi->getResetPassPhone();
			if ($oForm->validate()) {
				//делаем запрос на повторную отправку смс
				if (Yii::app()->adminKreddyApi->resetPasswordSendSms($oForm->phone, true)) {
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

		$oCodeForm->setAttributes($aPost);
		$oCodeForm->phone = Yii::app()->adminKreddyApi->getResetPassPhone();
		if ($oCodeForm->validate()) {
			$bResult = Yii::app()->adminKreddyApi->resetPasswordCheckSms($oCodeForm->phone, $oCodeForm->smsCode);
			//проверяем, удалось ли отправить смс
			if ($bResult) {
				//переходим на страницу "успешно"
				$this->redirect(Yii::app()->createUrl("/account/resetPassSmsSentSuccess"));
			} else {
				//добавляем ошибку перед выводом формы
				$oCodeForm->addError('smsCode', Yii::app()->adminKreddyApi->getLastSmsMessage());
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

		if (Yii::app()->user->isGuest) {
			$model = new AccountLoginForm;

			// if it is ajax validation request
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if (isset($_POST['AccountLoginForm'])) {
				$model->attributes = $_POST['AccountLoginForm'];
				// validate user input and redirect to the previous page if valid
				if ($model->validate() && $model->login()) {
					$this->redirect(Yii::app()->createUrl("/account"));
				}
			}
			// display the login form
			$this->render('login', array('model' => $model));
		} else {
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

}
