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

<h4>Ваш Пакет займов</h4>

<h5>Нет активных Пакетов</h5>

<?php
// если есть статус, выводим его
if (Yii::app()->adminKreddyApi->getStatusMessage()) {
	?>
	<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
	<br />
<?php
}

// если есть мораторий на подписку, то выводим его
if (Yii::app()->adminKreddyApi->getMoratoriumSubscription()) {
	?>
	Вы можете отправить заявку <?= Yii::app()->adminKreddyApi->getMoratoriumSubscription() ?>
	<br />
<?php
}
?>
<br />
<?= $passFormRender // отображаем форму запроса SMS-пароля?>
