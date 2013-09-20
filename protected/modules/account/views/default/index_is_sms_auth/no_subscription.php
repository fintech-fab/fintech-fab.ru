<?php
/**
 * @var $this DefaultController
 *
 * Нет подписки
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подключения';

// Если нет пакетов
?>

<h4>Ваш пакет займов</h4>

<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
<?php
// если есть мораторий на подписку, то выводим его
if (Yii::app()->adminKreddyApi->getMoratoriumSubscription()) {
	?>
	<strong>Новый пакет Вы можете оформить
		после:</strong> <?= Yii::app()->adminKreddyApi->getMoratoriumSubscription() ?>
	<br />
<?php
}
?>

