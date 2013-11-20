<?php
/**
 * @var $this Controller
 */


if (SiteParams::getIsIvanovoSite()) {
	$this->menu = array(
		array(
			'label' => 'Статус займа', 'url' => array('/account/default/index'),
		)
	);
} else {
	$this->menu = array(
		array(
			'label' => 'Ваш Пакет займов', 'url' => array('/account/default/index'),
		)
	);
}

$this->menu[] = array(
	'label' => 'История операций', 'url' => array('/account/default/history')
);


if (Yii::app()->adminKreddyApi->checkSubscribe()) {
	if (SiteParams::getIsIvanovoSite()) {
		$this->menu[] = array(
			'label' => 'Оформление займа', 'url' => array('/account/default/subscribe'),
		);
	} else {
		$this->menu[] = array(
			'label' => 'Подключение Пакета займов', 'url' => array('/account/default/subscribe'),
		);
	}
}
if (Yii::app()->adminKreddyApi->checkLoan()) {
	$this->menu[] = array(
		'label' => 'Оформление займа', 'url' => array('/account/default/loan')
	);
}
if (Yii::app()->adminKreddyApi->getBalance() < 0) {
	$this->menu[] = array(
		'label' => 'Оплатить задолженность', 'url' => 'https://pay.kreddy.ru/'
	);
}
$this->menu[] = array(
	'label' => 'Привязка банковской карты', 'url' => array('/account/default/addCard')
);
$this->menu[] = '';
$this->menu[] = array(
	'label' => 'Изменение паспортных данных', 'url' => array('/account/default/changePassport')
);
$this->menu[] = array(
	'label' => 'Изменение секретного вопроса', 'url' => array('/account/default/changeSecretQuestion')
);
$this->menu[] = array(
	'label' => 'Изменение цифрового кода', 'url' => array('/account/default/changeNumericCode')
);
$this->menu[] = array(
	'label' => 'Изменение пароля', 'url' => array('/account/default/changePassword')
);
$this->menu[] = '';
$this->menu[] = array(
	'label' => 'Выход', 'url' => array('/account/default/logout')
);


if (Yii::app()->adminKreddyApi->getBalance() < 0) {
	$sBalanceMessage = '<strong>Задолженность:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
	$sExpireToMessage = '<strong>Вернуть:</strong> ' . Yii::app()->adminKreddyApi->getActiveLoanExpiredTo() . '<br/>';
} else {
	$sBalanceMessage = '<strong>Баланс:</strong> ' . Yii::app()->adminKreddyApi->getAbsBalance() . ' руб. <br/>';
	$sExpireToMessage = '';
}

if (Yii::app()->adminKreddyApi->getActiveLoanExpired()) {
	$sExpiredMessage = '<strong>Платеж просрочен!</strong><br/>';
} else {
	$sExpiredMessage = '';
}
?>

<div class="well" style="padding: 8px; 0; margin-top: 20px;">
	<?php

	$this->beginWidget('bootstrap.widgets.TbMenu', array(
		'type'          => 'list', // '', 'tabs', 'pills' (or 'list')
		'stacked'       => true, // whether this is a stacked menu
		'items'         => $this->menu,
		'activateItems' => true,
		'htmlOptions'   => array('style' => 'margin-bottom: 0;'),
	));
	?>

	<div style="padding-left: 20px;">
		<h4><?= Yii::app()->adminKreddyApi->getClientFullName(); ?></h4>

		<p>
			<?= $sExpiredMessage; ?>
			<?= $sBalanceMessage; ?>
			<?= $sExpireToMessage ?>
		</p></div>
	<?php $this->endWidget(); ?>

</div>
