<?php
/**
 * @var $this DefaultController
 *
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

// Если нет пакетов и нет запросов
?>

<h4>Ваш пакет займов</h4>

<h5>Нет активных пакетов</h5>

<?php
// если есть статус, выводим его
if (Yii::app()->adminKreddyApi->getStatusMessage()) {
	?>
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
	<br /><br />
<?php
}

// если есть мораторий на подписку, то выводим его
if (Yii::app()->adminKreddyApi->getMoratoriumSubscription()) {
	?>
	Вы можете отправить заявку <?= Yii::app()->adminKreddyApi->getMoratoriumSubscription() ?>
	<br />
<?php
}

// если старая подписка кончилась, действующих займов/подписок нет
if (Yii::app()->adminKreddyApi->checkSubscribe() || Yii::app()->adminKreddyApi->checkLoan()) {
	echo CHtml::link('Посмотреть историю операций', Yii::app()->createUrl('account/history'));

	if (Yii::app()->adminKreddyApi->checkSubscribe()) {
		echo " &middot; " . CHtml::link('Оформить пакет займов', Yii::app()->createUrl('account/subscribe'));
	}

	if (Yii::app()->adminKreddyApi->checkLoan()) {
		echo " &middot; " . CHtml::link('Оформить займ', Yii::app()->createUrl('account/loan'));
	}
}
?>

