<?
/* @var $sRequestType */

$this->pageTitle = Yii::app()->name . ' - Подтверждение Email'; ?>

	<h2>Подтверждение Email</h2><br>
<?
if ($sRequestType === 'confirm') {
	?>
	<h2>Спасибо, Ваш email подтвержден</h2>
<? } elseif ($sRequestType === 'unsubscribe') { ?>
	<h2>Спасибо, Вы отписались от рассылки</h2>
<? } elseif ($sRequestType === 'broken') { ?>
	<h2>Спасибо, Ваш email не действителен</h2>
<? } elseif ($sRequestType === 'codeError') { ?>
	<h2>Код сообщения содержит ошибку!</h2>
<? }