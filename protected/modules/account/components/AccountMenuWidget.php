<?php

/**
 * Class AccountMenuWidget
 */
class AccountMenuWidget extends CWidget
{
	protected $aMenu;

	protected $sIndexLabel = 'Ваш Пакет займов';
	protected $sCancelRequestLabel = 'Отказаться/изменить текущий тариф';
	protected $sHistoryLabel = 'История операций';
	protected $sSubscribeLabel = 'Подключение Пакета займов';
	protected $sLoanLabel = 'Получить деньги';

	public function run()
	{
		$this->generateMenu();

		$this->render('menu');
	}

	protected function generateMenu()
	{
		$this->aMenu = array(
			array(
				'label' => $this->sIndexLabel,
				'url'   => array('/account/default/index'),
			)
		);

		if (Yii::app()->adminKreddyApi->getIsCanCancelRequest()) {
			$this->aMenu[] = array(
				'label' => $this->sCancelRequestLabel,
				'url'   => array('/account/cancelRequest')
			);
		}
		$this->aMenu[] = array(
			'label' => $this->sHistoryLabel,
			'url'   => array('/account/default/history')
		);


		if (Yii::app()->adminKreddyApi->checkSubscribe()) {

			$this->aMenu[] = array(
				'label' => $this->sSubscribeLabel,
				'url'   => array('/account/default/subscribe')
			);

		}
		if (Yii::app()->adminKreddyApi->isSubscriptionAwaitingConfirmationStatus()) {

			$this->aMenu[] = array(
				'label' => $this->sSubscribeLabel,
				'url'   => Yii::app()->createUrl('account/doSubscribeConfirm'),
			);

		}

		if (Yii::app()->adminKreddyApi->checkLoan()) {
			$this->aMenu[] = array(
				'label' => $this->sLoanLabel,
				'url'   => array('/account/default/loan')
			);
		}
		if (
			Yii::app()->adminKreddyApi->getBalance() < 0 &&
			!Yii::app()->adminKreddyApi->isSubscriptionAwaitingConfirmationStatus()
		) {
			$this->aMenu[] = array(
				'label' => 'Оплатить задолженность',
				'url'   => Yii::app()->params['payUrl'],
			);
		}
		$this->aMenu[] = array(
			'label' => 'Привязка банковской карты',
			'url'   => array('/account/default/addCard')
		);
		$this->aMenu[] = '';
		$this->aMenu[] = array(
			'label' => 'Мои условия',
			'items' => array(
				array(
					'label' => 'Мой договор',
					'url'   => array('/account/default/getDocumentList')
				),
				array(
					'label' => 'График платежей',
					'url'   => array('/account/default/paymentSchedule')
				),
			),
		);
		$this->aMenu[] = '';

		$this->aMenu[] = array(
			'label' => 'Изменение личных данных',
			'items' => array(
				array(
					'label' => 'Изменение паспортных данных',
					'url'   => array('/account/default/changePassport')
				),
				array(
					'label' => 'Изменение секретного вопроса',
					'url'   => array('/account/default/changeSecretQuestion')
				),
				array(
					'label' => 'Изменение цифрового кода',
					'url'   => array('/account/default/changeNumericCode')
				),
				array(
					'label' => 'Изменение пароля',
					'url'   => array('/account/default/changePassword')
				),
				array(
					'label' => 'Изменение адреса электронной почты',
					'url'   => array('/account/default/changeEmail')
				),
			),
		);

		$this->aMenu[] = array(
			'label' => 'Настройки безопасности',
			'url'   => array('/account/default/changeSmsAuthSetting')
		);

		$this->aMenu[] = '';
		$this->aMenu[] = array(
			'label' => 'Идентификация',
			'items' => array(
				array(
					'label' => 'На сайте',
					'url'   => array('/account/default/identifySite')
				),
				array(
					'label' => 'Загрузить фото',
					'url'   => array('/account/default/identifyPhoto')
				),
				array(
					'label' => 'На смартфоне',
					'url'   => array('/account/default/identifyApp')
				),
				array(
					'label'       => 'Инструкция',
					'url'         => array(Yii::app()->request->url . '#'),
					'linkOptions' => array(
						'onClick' => 'return doOpenModalFrame(\'/pages/viewPartial/videoInstruction\', \'Инструкция\')',
					),
				),
			),
		);


		$this->aMenu[] = '';
		$this->aMenu[] = array(
			'label' => 'Выход',
			'url'   => array('/account/default/logout')
		);

	}

	protected function renderBalanceInfo()
	{

		if (Yii::app()->adminKreddyApi->isSubscriptionAwaitingConfirmationStatus()) {
			return;
		}

		if (Yii::app()->adminKreddyApi->getCurrentProductExpired()) {
			echo '<strong>Платеж просрочен!</strong><br/>';
		}

		// если баланс отрицательный и подписка активна, выводим информацию о задолженности
		if (Yii::app()->adminKreddyApi->getBalance() < 0 && Yii::app()->adminKreddyApi->isSubscriptionActive()) {
			echo '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб.';
			echo '<a href="#" class="dashed" onclick="$(\'#detail_balance_info\').toggle(); return false;">детализация</a><br/>';
			echo '<div id="detail_balance_info" class="hide">';
			echo '<strong>Тело:</strong> ' . Yii::app()->adminKreddyApi->getAbsLoanBalance() . ' руб. <br/>';
			echo '<strong>Абонентская плата:</strong> ' . Yii::app()->adminKreddyApi->getAbsSubscriptionBalance() . ' руб. <br/>';
			if (Yii::app()->adminKreddyApi->getAbsFine() != 0) {
				echo '<strong>Неустойка:</strong> ' . Yii::app()->adminKreddyApi->getAbsFine() . ' руб. <br/>';
			}
			if (Yii::app()->adminKreddyApi->getAbsPenalty() != 0) {
				echo '<strong>Штраф:</strong> ' . Yii::app()->adminKreddyApi->getAbsPenalty() . ' руб. <br/>';
			}
			if (Yii::app()->adminKreddyApi->getAbsPercent() != 0) {
				echo '<strong>Проценты:</strong> ' . Yii::app()->adminKreddyApi->getAbsPercent() . ' руб. <br/>';
			}
			echo '</div>';
		} elseif (Yii::app()->adminKreddyApi->getBalance() >= 0) {
			echo '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getBalance() . ' руб. <br/>';
		}

	}
} 