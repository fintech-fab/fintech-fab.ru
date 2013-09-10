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
				'actions' => array('login', 'resetPassword', 'ajaxResetPassSendSmsCode', 'resetPassSendSmsPass', 'resetPassSmsSentSuccess'),
				'users'   => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('logout', 'index', 'ajaxSendSms', 'checkSmsPass', 'history', 'test'),
				'users'   => array('@'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Главная страница личного кабинета
	 */
	//TODO: однотипный код (в actionHistory практически такой же) не копипастить
	public function actionIndex()
	{
		if (Yii::app()->adminKreddyApi->isAuth()) {
			if (Yii::app()->adminKreddyApi->isSmsAuth()) {
				//выбираем представление в зависимости от статуса СМС-авторизации
				$sView = 'index_is_sms_auth';
			} else {
				$sView = 'index_not_sms_auth';
			}
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$oSmsPassForm = new SMSPasswordForm();
			if (!Yii::app()->adminKreddyApi->checkSmsPassSent()) {
				$sResetPassView = 'sms_password/send_password';
			} else {
				$sResetPassView = 'sms_password/check_password';
			}
			$sPassFormRender = $this->renderPartial($sResetPassView, array('model' => $oSmsPassForm, 'smsLeftTime' => Yii::app()->adminKreddyApi->getSmsPassLeftTime(), 'act' => 'index'), true);
			//если запрос пришел через AJAX, то рендерим представление без layouts/main
			if (Yii::app()->request->isAjaxRequest) {
				$this->layout = '/layouts/column2_ajax';
				$this->renderWithoutProcess($sView, array('passFormRender' => $sPassFormRender));
			} else {
				$this->render($sView, array('passFormRender' => $sPassFormRender));
			}
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * История операций
	 */

	public function actionHistory()
	{
		//получаем историю операций из API
		$oHistoryDataProvider = Yii::app()->adminKreddyApi->getHistoryDataProvider();

		if (Yii::app()->adminKreddyApi->isAuth()) {
			//выбираем представление в зависимости от статуса СМС-авторизации
			if (Yii::app()->adminKreddyApi->isSmsAuth()) {
				$sView = 'history_is_sms_auth';
			} else {
				$sView = 'history_not_sms_auth';
			}
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$oSmsPassForm = new SMSPasswordForm();
			if (!Yii::app()->adminKreddyApi->checkSmsPassSent()) {
				$sResetPassView = 'sms_password/send_password';
			} else {
				$sResetPassView = 'sms_password/check_password';
			}
			$sPassFormRender = $this->renderPartial($sResetPassView, array('model' => $oSmsPassForm, 'smsLeftTime' => Yii::app()->adminKreddyApi->getSmsPassLeftTime(), 'act' => 'history'), true, false);
			//если запрос пришел через AJAX, то рендерим представление без layouts/main
			if (Yii::app()->request->isAjaxRequest) {
				$this->layout = '/layouts/column2_ajax';
				$this->renderWithoutProcess($sView, array('passFormRender' => $sPassFormRender, 'historyProvider' => $oHistoryDataProvider));
			} else {
				$this->render($sView, array('passFormRender' => $sPassFormRender, 'historyProvider' => $oHistoryDataProvider));
			}
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}


	/**
	 * Запрос на отправку SMS с паролем (для доступа к приватным данным в личном кабинете)
	 */

	public function actionAjaxSendSms($resend = 0)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$bResend = (boolean)$resend;

			if (!$bResend && Yii::app()->adminKreddyApi->checkSmsPassSent()) {
				$aAnswer = array(
					"sms_code"    => 2,
					"sms_message" => "SMS уже отправлено",
				);
				echo CJSON::encode($aAnswer);
				Yii::app()->end();
			}

			if ($bResend && Yii::app()->adminKreddyApi->getSmsPassLeftTime() > 0) {
				$aAnswer = array(
					"sms_code"    => 2,
					"sms_message" => SiteParams::API_MINUTES_RESEND_ERROR,
				);
				echo CJSON::encode($aAnswer);
				Yii::app()->end();
			}

			$aResult = Yii::app()->adminKreddyApi->sendSmsPassword($bResend);

			//TODO: протестить
			if ($aResult && $aResult['code'] == 10 && $aResult['sms_status'] == 1) {
				//устанавливаем флаг "СМС отправлено" и время отправки
				Yii::app()->adminKreddyApi->setSmsPassSentAndTime();
			}

			if (empty($aResult['sms_message'])) {
				$aResult['sms_message'] = '';
			}

			if (isset($aResult['sms_status'])) {
				switch ($aResult['sms_status']) {
					case 1:
						$iSmsCode = 0; // успешно! переходим на след. форму
						$aResult['sms_message'] = Yii::app()->createUrl("/account/checkSmsPass");
						break;
					default:
						$iSmsCode = 3;
						break;
				}
			} else {
				$iSmsCode = 3;
			}

			$aAnswer = array(
				"sms_code"    => $iSmsCode,
				"sms_message" => $aResult['sms_message'],
			);

			echo CJSON::encode(array_merge($aAnswer, array(
				"sms_left_time" => Yii::app()->adminKreddyApi->getSmsPassLeftTime(),
			)));
		}
		Yii::app()->end();
	}

	/**
	 * Проверка SMS-пароля в личном кабинете (доступ к приватным данным)
	 *
	 * @param string $act
	 */
	public function actionCheckSmsPass($act = 'index')
	{
		if (Yii::app()->request->isAjaxRequest) {
			$aAnswer = array(
				"sms_code"    => 2,
				"sms_message" => 'Неверный пароль!',
			);

			$bIsPost = Yii::app()->request->getIsPostRequest();
			$aPasswordForm = Yii::app()->request->getParam('SMSPasswordForm');

			if ($bIsPost && isset($aPasswordForm)) {
				$passForm = new SMSPasswordForm();
				$aPostData = $_POST['SMSPasswordForm'];
				$passForm->setAttributes($aPostData);

				if ($passForm->validate()) {
					$aResult = Yii::app()->adminKreddyApi->getSmsAuth($passForm->smsPassword);
					if ($aResult['sms_status'] == AdminKreddyApiComponent::SMS_AUTH_OK) {
						$aAnswer = array(
							"sms_code"    => 0,
							"sms_message" => Yii::app()->createUrl("account/" . $act),
						);
					} elseif ($aResult['sms_status'] == AdminKreddyApiComponent::SMS_CODE_TRIES_EXCEED) { //превышено число попыток ввода пароля
						$aAnswer = array(
							"sms_code"    => 2,
							"sms_message" => 'Вы превысили допустимое число попыток ввода пароля!',
						);
					}
				}
			}
			echo CJSON::encode($aAnswer);
		} else {
			$this->layout = '/layouts/column1';

			$model = new SMSPasswordForm();

			if (!Yii::app()->adminKreddyApi->checkSmsPassSent()) {
				$this->render('reset_password/send_password', array('model' => $model,));
			} else {
				$this->render('reset_password/check_password', array('model' => $model,));
			}
		}
	}

	/**
	 *  Форма восстановления пароля, необходимого для входа в личный кабинет
	 */
	public function actionResetPassword()
	{
		$this->layout = '/layouts/column1';

		if (Yii::app()->user->isGuest) {
			$model = new AccountResetPasswordForm;
			if (Yii::app()->adminKreddyApi->checkResetPassPhone()) {
				$this->render('reset_password/send_password', array('model' => $model,));
			} else {
				$this->render('reset_password/send_code', array('model' => $model,));
			}
		} else {
			$this->redirect(Yii::app()->createUrl("/account"));
		}
	}

	/**
	 * Отправка на телефон SMS с кодом (для дальнейшей идентификации)
	 * Если SMS отсылается впервые, дополнительно проводится проверка телефона на валидность
	 *
	 * @param int $resend - повторная ли отправка SMS с кодом
	 */
	public function actionAjaxResetPassSendSmsCode($resend = 0)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$bResend = (boolean)$resend;
			$sPhone = "";
			$aAnswer = array(
				"sms_code"    => 2,
				"sms_message" => 'Неверный пароль!',
			);

			// если SMS отправляется впервые, то валидириуем данные и записываем в переменную $phone
			if (!$bResend) {
				if (isset($_POST['AccountResetPasswordForm'])) {
					$model = new AccountResetPasswordForm('phoneRequired');
					$model->attributes = $_POST['AccountResetPasswordForm'];
					if (!$model->validate()) {
						echo CJSON::encode(array(
							"sms_code"    => 2,
							"sms_message" => "Введите правильный телефон",
						));
						Yii::app()->end();
					} else {
						if (Yii::app()->adminKreddyApi->checkResetPassSmsCodeSent()) {
							echo CJSON::encode(array(
								"sms_code"    => 2,
								"sms_message" => "SMS уже отправлено",
							));
							Yii::app()->end();
						}
					}
					// берём телефон
					$sPhone = $model->phone;
				} else {
					echo CJSON::encode(array(
						"sms_code"    => 2,
						"sms_message" => "Введите телефон",
					));
					Yii::app()->end();
				}
			} else {
				// если SMS отправляется повторно, но время до разрешённой переотправки ещё не наступило, выдаём ошибку
				if (Yii::app()->adminKreddyApi->getResetPassSmsCodeLeftTime() > 0) {
					echo CJSON::encode(array(
						"sms_code"    => 2,
						"sms_message" => SiteParams::API_MINUTES_RESEND_ERROR,
					));
					Yii::app()->end();
				}

				// если повторная отправка, то берём телефон из сессии
				$sPhone = Yii::app()->adminKreddyApi->getResetPassPhone();
			}
			$aResult = Yii::app()->adminKreddyApi->resetPasswordSendSms($sPhone, $bResend);

			/**
			 * если API сообщило, что требуется SMS-код и он отправлен клиенту, то записываем
			 * в сессию время отправки, время до разрешённой переотправки и телефон
			 */
			if ($aResult && $aResult['code'] == 10 && $aResult['sms_status'] == 1) {
				Yii::app()->adminKreddyApi->setResetPassSmsCodeSentAndTime();
				Yii::app()->adminKreddyApi->setResetPassPhone($sPhone);
			}

			if (empty($aResult['sms_message'])) {
				$aResult['sms_message'] = '';
			}

			if (isset($aResult['sms_status'])) {
				switch ($aResult['sms_status']) {
					// $iSmsCode = 0 - ошибок нет
					case 1:
						$iSmsCode = 0;
						$aResult['sms_message'] = Yii::app()->createUrl("/account/resetPassSendSmsPass");
						break;
					default:
						$iSmsCode = 3;
						break;
				}
			} else {
				$iSmsCode = 3;
				$aResult['sms_message'] = 'Произошла неизвестная ошибка. Обратитесь в горячую линию.';
			}

			echo CJSON::encode(array(
				"sms_code"      => $iSmsCode,
				"sms_message"   => $aResult['sms_message'],
				"sms_left_time" => Yii::app()->adminKreddyApi->getResetPassSmsCodeLeftTime(),
			));
		}
		Yii::app()->end();
	}

	/**
	 * Проверка кода, отправленного в SMS. Если код верен - отправка SMS с паролем на телефон из сессии
	 */
	public function actionResetPassSendSmsPass()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$aAnswer = array(
				"sms_code"    => 2,
				"sms_message" => 'Неверный код!',
			);

			// если в сессии нет телефона либо если пароль уже отправлен
			if (Yii::app()->adminKreddyApi->checkResetPassPhone()) {
				$codeForm = new AccountResetPasswordForm('codeRequired');
				$aPostData = $_POST['AccountResetPasswordForm'];
				$codeForm->setAttributes($aPostData);
				$codeForm->phone = Yii::app()->adminKreddyApi->getResetPassPhone();

				if ($codeForm->validate()) {
					$aResult = Yii::app()->adminKreddyApi->resetPasswordCheckSms($codeForm->phone, $codeForm->smsCode);

					if ($aResult['sms_status'] == AdminKreddyApiComponent::SMS_AUTH_OK) {
						$aAnswer = array(
							"sms_code"    => 0,
							//"sms_message" => 'SMS с паролем отправлено',
							"sms_message" => Yii::app()->createUrl("/account/resetPassSmsSentSuccess"),
						);
					} else {
						if ($aResult['sms_status'] == AdminKreddyApiComponent::SMS_CODE_TRIES_EXCEED) { //превышено число попыток ввода пароля
							$aAnswer = array(
								"sms_code"    => 2,
								"sms_message" => 'Вы превысили допустимое число попыток ввода пароля!',
							);
						}
					}
				}
			} else {
				$aAnswer = array(
					"sms_code"    => 2,
					"sms_message" => 'Неизвестная ошибка! Перезагрузите страницу.',
				);
			}
			echo CJSON::encode($aAnswer);
			Yii::app()->end();
		} else {
			$this->layout = '/layouts/column1';

			// если в сессии нет телефона, то перенаправляем на форму ввода телефона
			if (!Yii::app()->adminKreddyApi->checkResetPassPhone()) {
				$this->redirect(Yii::app()->createUrl("account/resetPassword"));
			}

			$model = new AccountResetPasswordForm;
			$this->render('reset_password/send_password', array('model' => $model,));
		}
	}

	/**
	 *
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

	/**
	 * @param      $view
	 * @param null $data
	 * @param bool $return
	 *
	 * @return string
	 */

	public function renderWithoutProcess($view, $data = null, $return = false)
	{
		if ($this->beforeRender($view)) {
			$output = $this->renderPartial($view, $data, true);
			if (($layoutFile = $this->getLayoutFile($this->layout)) !== false) {
				$output = $this->renderFile($layoutFile, array('content' => $output), true);
			}

			$this->afterRender($view, $output);

			if ($return) {
				return $output;
			} else {
				echo $output;
			}
		}

		return false;
	}
}
