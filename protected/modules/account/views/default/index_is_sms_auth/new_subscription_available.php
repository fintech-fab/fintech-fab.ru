<?php
/**
 * @var $this DefaultController
 *
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Ваш Пакет займов';

// если можно оформить новый пакет
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

<br />

<div class="well">
	<?php    $this->widget('bootstrap.widgets.TbButton', array(
		'label' => 'Подключить Пакет', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
			->createUrl('account/subscribe'),
	));?>

</div>
