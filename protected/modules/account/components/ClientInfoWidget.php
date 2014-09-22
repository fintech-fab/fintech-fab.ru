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

		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_LOAN             => 'Перевод денег временно недоступен',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SCORING          => 'Заявка отклонена',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SUBSCRIPTION     => 'Подключение сервиса временно недоступно',

		AdminKreddyApiComponent::C_SUBSCRIPTION_ACTIVE                => 'Сервис подключен',
		AdminKreddyApiComponent::C_SUBSCRIPTION_AVAILABLE             => 'Подключение к сервису доступно',
		AdminKreddyApiComponent::C_SUBSCRIPTION_CANCEL                => 'Срок внесения абонентки истек',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAID                  => 'Сервис подключен, теперь ты можешь пользоваться деньгами',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAYMENT               => 'Поздравляем! Заявка на {loan_amount} р. одобрена. Оплати абонентку в размере {sub_pay_sum} рублей для подключения сервиса на месяц. {payments_url_start}Подробнее{payments_url_end}',
		AdminKreddyApiComponent::C_SUBSCRIPTION_AWAITING_CONFIRMATION => 'Поздравляем! Заявка на {loan_amount} р. одобрена. Теперь ты можешь подключить сервис КРЕДДИ',

		AdminKreddyApiComponent::C_SCORING_PROGRESS                   => 'Твоя заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+
		AdminKreddyApiComponent::C_SCORING_AWAITING_REIDENTIFY        => 'Необходимо пройти повторную идентификацию',
		AdminKreddyApiComponent::C_SCORING_ACCEPT                     => 'Твоя заявка одобрена. Деньги успешно отправлены.',
		AdminKreddyApiComponent::C_SCORING_CANCEL                     => 'Заявка отклонена',

		AdminKreddyApiComponent::C_LOAN_DEBT                          => 'Непогашенная задолженность',
		AdminKreddyApiComponent::C_LOAN_ACTIVE                        => 'Деньги отправлены', //+
		AdminKreddyApiComponent::C_LOAN_TRANSFER                      => 'Деньги отправлены', //+
		AdminKreddyApiComponent::C_LOAN_AVAILABLE                     => 'Сервис подключен, теперь ты можешь пользоваться деньгами',
		AdminKreddyApiComponent::C_LOAN_CREATED                       => 'Деньги отправлены', //+
		AdminKreddyApiComponent::C_LOAN_PAID                          => 'Спасибо! Мы все получили. Задолженность полностью погашена.',
		AdminKreddyApiComponent::C_LOAN_REQUEST                       => 'Необходимо подтвердить условия',
		AdminKreddyApiComponent::C_LOAN_CONFIRMED                     => 'Индивидуальные условия приняты',

		AdminKreddyApiComponent::C_CLIENT_ACTIVE                      => 'Подключение сервиса доступно', //+
		AdminKreddyApiComponent::C_CLIENT_NEW                         => 'Подключи сервис',
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
		<strong>Пакет:</strong> <?= Yii::app()->adminKreddyApi->getSubscriptionRequestName() ?><br />
	<?php

	}

	/**
	 * @return string
	 */
	protected function getHeader()
	{
		return $this->sTitle;
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
		if (Yii::app()->adminKreddyApi->getCurrentProductLoanExpiredTo()) {
			// если есть займ, выводим дату возврата
			?>
			<strong>Срок возврата денег (в любое время)
				до:</strong> <?= Yii::app()->adminKreddyApi->getCurrentProductLoanExpiredTo() ?><br />
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
			Ты можешь отправить запрос на перевод денег <?= Yii::app()->adminKreddyApi->getMoratoriumLoan() ?>
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
			Ты можешь подключить сервис <?= Yii::app()->adminKreddyApi->getMoratoriumSubscriptionLoan() ?>
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
