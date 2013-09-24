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
	<br /><br />
<?php
}

$this->widget(
	'bootstrap.widgets.TbButton',
	array(
		'label' => 'Посмотреть историю операций',
		'size'  => 'small',
		'icon'  => 'icon-list-alt',
		'url'   => Yii::app()->createUrl('account/history'),
	)
);
?>

<div class="clearfix"></div>

<?php
// если есть мораторий на подписку, то выводим его
if (Yii::app()->adminKreddyApi->getMoratoriumSubscription()) {

	echo "<br/>" . CHtml::openTag("div", array("class" => "well")) . "Вы можете отправить новую заявку " . Yii::app()->adminKreddyApi->getMoratoriumSubscription() . "<br/>";

	echo CHtml::closeTag("div");
}

// если можно оформить новый пакет
if (Yii::app()->adminKreddyApi->checkSubscribe()) {

	echo "<br />" . CHtml::openTag("div", array("class" => "well"));

	$this->widget('bootstrap.widgets.TbButton', array(
		'label' => 'Подключить Пакет', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
			->createUrl('account/subscribe'),
	));

	echo CHtml::closeTag("div");
}?>

