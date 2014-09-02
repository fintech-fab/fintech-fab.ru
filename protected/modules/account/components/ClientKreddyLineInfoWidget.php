<?php

/**
 * Class ClientKreddyLineInfoWidget
 */
class ClientKreddyLineInfoWidget extends ClientInfoWidget
{
	protected $sTitle = 'Твой КРЕДДИ';
	protected $sNoActiveProducts = 'Сервис не подключен';
	protected $sSubscribeButtonLabel = 'Подключить КРЕДДИ';

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

	protected function renderProduct()
	{
		?>
		<strong><?= Yii::app()->adminKreddyApi->getSubscriptionProduct() ?></strong><br />
	<?php
	}

	public function renderProductRequest()
	{
		?>
		<strong><?= Yii::app()->adminKreddyApi->getSubscriptionRequestName() ?></strong><br />
	<?php

	}

	protected function renderAvailableLoans()
	{
		if (Yii::app()->adminKreddyApi->getSubscriptionActivity()) {
			?>
			<strong>Серивс активен до:</strong>
			<?= Yii::app()->adminKreddyApi->getSubscriptionActivity() ?>
			<br />
		<?php
		}


	}

} 