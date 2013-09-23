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
	?>
	Вы можете отправить заявку <?= Yii::app()->adminKreddyApi->getMoratoriumSubscription() ?>
	<br />
<?php
}

// если можно оформить новый пакет
if (Yii::app()->adminKreddyApi->checkSubscribe()) {

	echo "<br/>" . CHtml::openTag("div", array("class" => "well")) . "Доступно новое подключение! <br/> <br/> ";

	$this->widget(
		'bootstrap.widgets.TbButton',
		array(
			'label' => 'Оформить пакет займов',
			'icon'  => "icon-ok icon-white",
			'type'  => 'primary',
			'size'  => 'small',
			'url'   => Yii::app()->createUrl('account/subscribe'),
		)
	);

	echo CHtml::closeTag("div");
}
?>

