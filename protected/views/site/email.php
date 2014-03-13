<?php

/* @var $sRequestType */

$this->pageTitle = Yii::app()->name . ' - Подтверждение Email'; ?>

	<h2>Подтверждение Email</h2><br>
<?php
if ($sRequestType === 'confirm') {
	?>
	<h2>Спасибо, Ваш email подтвержден</h2>
<?php } elseif ($sRequestType === 'unsubscribe') { ?>
	<h2>Спасибо, Вы отписались от рассылки</h2>
<?php } elseif ($sRequestType === 'broken') { ?>
	<h2>Спасибо, Ваш email не действителен</h2>
<?php } elseif ($sRequestType === 'codeError') { ?>
	<h2>Код сообщения содержит ошибку!</h2>
<?php }