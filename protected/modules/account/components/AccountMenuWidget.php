<?php

/**
 * Class AccountMenuWidget
 */
class AccountMenuWidget extends CWidget
{
	protected $aMenu;

	protected $sIndexLabel = 'Ваш Пакет займов';
	protected $sCancelRequestLabel = 'Отказаться/изменить текущие условия';
	protected $sHistoryLabel = 'История операций';
	protected $sSubscribeLabel = 'Подключение Пакета займов';
	protected $sLoanLabel = 'Оформление займа';

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
		if (Yii::app()->adminKreddyApi->checkLoan()) {
			$this->aMenu[] = array(
				'label' => $this->sLoanLabel,
				'url'   => array('/account/default/loan')
			);
		}
		if (Yii::app()->adminKreddyApi->getBalance() < 0) {
			$this->aMenu[] = array(
				'label' => 'Оплатить задолженность',
				'url'   => 'https://pay.kreddy.ru/'
			);
		}
		$this->aMenu[] = array(
			'label' => 'Привязка банковской карты',
			'url'   => array('/account/default/addCard')
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
		if (Yii::app()->adminKreddyApi->getActiveLoanExpired()) {
			echo '<strong>Платеж просрочен!</strong><br/>';
		}

		if (Yii::app()->adminKreddyApi->getBalance() < 0) {
			echo '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
			echo '<strong>Вернуть:</strong> ' . Yii::app()->adminKreddyApi->getActiveLoanExpiredTo() . '<br/>';
		} else {
			echo '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
		}

	}
} 