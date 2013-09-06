<?php /* @var $this Controller */


$sFullName = $this->clientData['client_data']['fullname'];
$bIsDebt = $this->clientData['client_data']['is_debt'];
$iBalance = $this->clientData['active_loan']['balance'];
$bExpired = $this->clientData['active_loan']['expired'];
$sExpiredTo = $this->clientData['active_loan']['expired_to'];

if (!$iBalance) {
	if (isset($bIsDebt) && $bIsDebt) {
		$sDebtMessage = '<strong>У вас есть задолженность по кредиту.</strong><br/>Авторизуйтесь по SMS-паролю для получения подробной информации.<br/>';
	} elseif (isset($bIsDebt) && !$bIsDebt) {
		$sDebtMessage = '<strong>У вас нет задолженности по кредиту.</strong><br/>Авторизуйтесь по SMS-паролю для получения подробной информации.<br/>';
	} else {
		$sDebtMessage = '<strong>Произошла ошибка!</strong><br/>Выйдите из личного кабинета и выполните вход заново.<br/>Если ошибка повторится, позвоните на горячую линию.';
	}

	$sBalanceMessage = '';
	$sExpireToMessage = '';

} else {
	$sDebtMessage = '';

	if ($iBalance < 0) {
		$sBalanceMessage = '<strong>Задолженность:</strong> ' . abs($iBalance) . ' руб. <br/>';
	} else {
		$sBalanceMessage = '<strong>Баланс:</strong> ' . abs($iBalance) . ' руб. <br/>';
	}
	$sExpireToMessage = '<strong>Вернуть до:</strong> ' . $sExpiredTo . '<br/>';
}

if ($bExpired) {
	$sExpireMessage = '<strong>Платеж просрочен!</strong><br/>';
} else {
	$sExpireMessage = '';
}


?>


<div class="row">
	<div class="span8">
		<h3 class="pay_legend">Личный кабинет</h3><br />
		<?php echo $content; ?>
	</div>
	<!-- content -->
	<div class="span4">
		<div class="well" style="padding: 8px; 0; margin-top: 20px;">
			<?php

			$this->beginWidget('bootstrap.widgets.TbMenu', array(
				'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
				'stacked'     => true, // whether this is a stacked menu
				'items'       => $this->menu,
				'htmlOptions' => array('style' => 'margin-bottom: 0;'),
			));
			?>

			<div style="padding-left: 20px;">
				<h4><?= $sFullName; ?></h4>

				<p>
					<?= $sExpireMessage; ?>
					<?= $sBalanceMessage; ?>
					<?= $sExpireToMessage ?>
					<?= $sDebtMessage ?>
				</p>
			</div>
			<?php $this->endWidget(); ?>

		</div>
		<!-- sidebar -->
	</div>
</div>
