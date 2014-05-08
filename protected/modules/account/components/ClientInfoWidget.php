<?php

/**
 * Class ClientInfoWidget
 */
class ClientInfoWidget extends CWidget
{
	public $sClientInfoView;


	protected $sTitle = 'Ваш Пакет займов';
	protected $sNoActiveProducts = 'Нет активных Пакетов';
	protected $sSubscribeButtonLabel = 'Подключить Пакет';

	protected $sErrorMessage = 'Ошибка!';
	protected $aAvailableStatuses = array(

		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_LOAN             => 'Временно недоступно получение новых займов',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SCORING          => 'Заявка отклонена',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SUBSCRIPTION     => 'Временно недоступно подключение новых Пакетов',

		AdminKreddyApiComponent::C_SUBSCRIPTION_ACTIVE                => 'Подключен к Пакету',
		AdminKreddyApiComponent::C_SUBSCRIPTION_AVAILABLE             => 'Доступно подключение к Пакету',
		AdminKreddyApiComponent::C_SUBSCRIPTION_CANCEL                => 'Срок оплаты подключения истек',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAID                  => 'Займ доступен',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAYMENT               => 'Оплатите подключение в размере {sub_pay_sum} рублей любым удобным способом. {payments_url_start}Подробнее{payments_url_end}',

		AdminKreddyApiComponent::C_SCORING_PROGRESS                   => 'Заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+

		AdminKreddyApiComponent::C_SUBSCRIPTION_AWAITING_CONFIRMATION => 'Заявка одобрена, теперь Вы можете взять займ',

		AdminKreddyApiComponent::C_SCORING_ACCEPT                     => 'Ваша заявка одобрена, ожидайте выдачи займа',
		AdminKreddyApiComponent::C_SCORING_CANCEL                     => 'Заявка отклонена',

		AdminKreddyApiComponent::C_LOAN_DEBT                          => 'Задолженность по займу',
		AdminKreddyApiComponent::C_LOAN_ACTIVE                        => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_TRANSFER                      => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_AVAILABLE                     => 'Займ доступен',
		AdminKreddyApiComponent::C_LOAN_CREATED                       => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_PAID                          => 'Займ оплачен',

		AdminKreddyApiComponent::C_CLIENT_ACTIVE                      => 'Доступно подключение Пакета', //+
		AdminKreddyApiComponent::C_CLIENT_NEW                         => 'Выберите Пакет займов',
		AdminKreddyApiComponent::C_CLIENT_FAST_REG                    => 'Требуется заполнить анкету',
	);

	public function run()
	{
		$this->setTitle();

		$this->render("ClientInfoWidget/" . $this->sClientInfoView);
	}

	protected function setTitle()
	{
		$this->controller->pageTitle = Yii::app()->name . ' - ' . $this->sTitle;
	}

	public function renderProductRequest()
	{
		?>
		<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?><br />
	<?php

	}

	/**
	 * @return string
	 */
	protected function getHeader()
	{
		return $this->sTitle;
	}

	protected function renderBalance()
	{
		if (
			Yii::app()->adminKreddyApi->getBalance() != 0 &&
			Yii::app()->adminKreddyApi->getClientStatus() != AdminKreddyApiComponent::C_SUBSCRIPTION_AWAITING_CONFIRMATION
		) {
			// выводим сообщение, если баланс не равен 0
			?>
			<strong>Задолженность:</strong>  <?= -Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
		<?php
		}
	}

	protected function renderProduct()
	{
		?>
		<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?><br />
	<?php

	}

	protected function renderNoProducts()
	{
		echo '<h5>' . $this->sNoActiveProducts . '</h5>';
	}

	protected function renderChannel()
	{
		?>
		<strong>Канал получения займа:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionChannel() ?><br />
	<?php
	}

	protected function renderStatus()
	{
		?>
		<strong>Статус:</strong> <?= $this->getStatusMessage() ?>
		&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameForStatus(); ?>
		<br />
	<?php
	}

	protected function renderLoanPayDate()
	{
		if (Yii::app()->adminKreddyApi->getActiveLoanExpiredTo()) {
			// если есть займ, выводим дату возврата
			?>
			<strong>Возврат займа (в любое время)
				до:</strong> <?= Yii::app()->adminKreddyApi->getActiveLoanExpiredTo() ?><br />
		<?php
		}

	}

	protected function renderAvailableLoans()
	{
		$iAvailableLoans = Yii::app()->adminKreddyApi->getSubscriptionAvailableLoans();

		if ($iAvailableLoans > 0): ?>
			<strong>Пакет активен до:</strong>  <?=
			(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
				Yii::app()->adminKreddyApi->getSubscriptionActivity()
				: "&mdash;"; ?>
			<br />

			<strong>Доступно займов:</strong> <?= $iAvailableLoans; ?><br />
		<?php endif;

	}

	protected function renderLoanAvailableDate()
	{
		?>
		<br />
		<div class="clearfix"></div>
		<div class="well">
			Вы можете оформить займ <?= Yii::app()->adminKreddyApi->getMoratoriumLoan() ?>
			<br />
		</div>
	<?php
	}

	protected function renderHistoryButton()
	{
		echo "<br/><p>";
		$this->controller->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label' => 'Посмотреть историю операций',
				'size'  => 'small',
				'icon'  => 'icon-list-alt',
				'url'   => Yii::app()->createUrl('account/history'),
			)
		);
		echo "</p>";
	}

	protected function renderSubscribeButton()
	{
		if (Yii::app()->adminKreddyApi->checkSubscribe()): ?>
			<div class="clearfix"></div>

			<br />
			<div class="well">
				<?php

				$this->widget('bootstrap.widgets.TbButton', array(
					'label' => $this->sSubscribeButtonLabel, 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
							->createUrl('account/subscribe'),
				));
				?>
			</div>
		<?php endif;

	}

	public function renderSubscriptionAvailableDate()
	{
		?>
		<div class="clearfix"></div>

		<br />
		<div class="well">
			Вы можете отправить новую заявку <?= Yii::app()->adminKreddyApi->getMoratoriumSubscriptionLoan() ?>
			<br />
		</div>
	<?php
	}

	/**
	 * Получение сообщения статуса (активен, в скоринге, ожидает оплаты)
	 * TODO вынести этот метод вообще в отдельный компонент?
	 *
	 * @return string|bool
	 */
	public function getStatusMessage()
	{

		$sStatusName = Yii::app()->adminKreddyApi->getClientStatus();

		$sStatus = (!empty($this->aAvailableStatuses[$sStatusName])) ? $this->aAvailableStatuses[$sStatusName] : $this->sErrorMessage;

		$sStatus = strtr($sStatus, Yii::app()->adminKreddyApi->formatStatusMessage());

		return $sStatus;
	}

}
