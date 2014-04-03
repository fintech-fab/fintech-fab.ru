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

	public function run()
	{
		$this->setTitle();

		$this->render(get_class($this) . "/" . $this->sClientInfoView);
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
		if (Yii::app()->adminKreddyApi->getBalance() != 0) {
			// выводим сообщение, если баланс не равен 0
			?>
			<strong>Баланс:</strong>  <?= Yii::app()->adminKreddyApi->getBalance(); ?> руб. <br />
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
		<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
		&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameForStatus(); ?>
		<br />
	<?php
	}

	protected function renderLoanPayDate()
	{
		if (Yii::app()->adminKreddyApi->getActiveLoanExpiredTo()) {
			// если есть займ, выводим дату возврата
			?>
			<strong>Возврат займа:</strong> <?= Yii::app()->adminKreddyApi->getActiveLoanExpiredTo() ?><br />
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
		echo "<br/>";
		$this->controller->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label' => 'Посмотреть историю операций',
				'size'  => 'small',
				'icon'  => 'icon-list-alt',
				'url'   => Yii::app()->createUrl('account/history'),
			)
		);
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

}
