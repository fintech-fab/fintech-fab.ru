<?php
/**
 * @var DefaultController $this
 * @var IkTbActiveForm    $form
 */

/*
 * Ввести пароль из SMS
 */

$this->pageTitle = Yii::app()->name . " - Восстановление пароля";
?>
<h2 class='pay_legend' style="margin-left: 20px;">Восстановить пароль</h2>
<div class="form" id="activeForm">
	<div class="row">

		<div class="alert in alert-success span10" id="smsAuthDone">
			<?= Dictionaries::C_SMS_RESET_PASSWORD_SUCCESS ?> <br><br> <?=
			CHtml::link('Выполнить вход &raquo;', Yii::app()
				->createUrl('/account/login')); ?>
		</div>

	</div>
</div>
