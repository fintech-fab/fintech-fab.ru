<?php

/**
 * Class ClientKreddyLineInfoWidget
 */
class ClientKreddyLineInfoWidget extends ClientInfoWidget
{
	protected $sTitle = 'Ваша КРЕДДИтная линия';
	protected $sNoActiveProducts = 'Нет активной КРЕДДИтной линии';
	protected $sSubscribeButtonLabel = 'Подключить КРЕДДИтную линию';

	protected $aAvailableStatuses = array(

		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_LOAN         => 'Временно недоступно получение новых займов',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SCORING      => 'Заявка отклонена',
		AdminKreddyApiComponent::C_CLIENT_MORATORIUM_SUBSCRIPTION => 'Временно недоступно подключение новой КРЕДДИтной линии',

		AdminKreddyApiComponent::C_SUBSCRIPTION_ACTIVE            => 'Подключен к КРЕДДИтной линии',
		AdminKreddyApiComponent::C_SUBSCRIPTION_AVAILABLE         => 'Доступно подключение к КРЕДДИтной линии',
		AdminKreddyApiComponent::C_SUBSCRIPTION_CANCEL            => 'Срок внесения абонентской платы истек',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAID              => 'Займ доступен',
		AdminKreddyApiComponent::C_SUBSCRIPTION_PAYMENT           => 'Внесите абонентскую плату в размере {sub_pay_sum} рублей любым удобным способом. {payments_url_start}Подробнее{payments_url_end}',

		AdminKreddyApiComponent::C_SCORING_PROGRESS               => 'Заявка в обработке. {account_url_start}Обновить статус{account_url_end}', //+

		AdminKreddyApiComponent::C_SCORING_ACCEPT                 => 'Ваша заявка одобрена, ожидайте выдачи займа',
		AdminKreddyApiComponent::C_SCORING_CANCEL                 => 'Заявка отклонена',

		AdminKreddyApiComponent::C_LOAN_DEBT                      => 'Задолженность по займу',
		AdminKreddyApiComponent::C_LOAN_ACTIVE                    => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_TRANSFER                  => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_AVAILABLE                 => 'Займ доступен',
		AdminKreddyApiComponent::C_LOAN_CREATED                   => 'Займ перечислен', //+
		AdminKreddyApiComponent::C_LOAN_PAID                      => 'Займ оплачен',

		AdminKreddyApiComponent::C_CLIENT_ACTIVE                  => 'Доступно подключение КРЕДДИтной линии', //+
		AdminKreddyApiComponent::C_CLIENT_NEW                     => 'Выберите КРЕДДИтную линию',
		AdminKreddyApiComponent::C_CLIENT_FAST_REG                => 'Требуется заполнить анкету',
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
		<strong><?= Yii::app()->adminKreddyApi->getSubscriptionRequest() ?></strong><br />
	<?php

	}

	protected function renderAvailableLoans()
	{
		?>
		<strong>КРЕДДИтная линия активна до:</strong>  <?=
		(Yii::app()->adminKreddyApi->getSubscriptionActivity()) ?
			Yii::app()->adminKreddyApi->getSubscriptionActivity()
			: "&mdash;"; ?>
		<br />
	<?php


	}

} 