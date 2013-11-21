<?php
/**
 * @var $this DefaultController
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

if (SiteParams::getIsIvanovoSite()) {
	$this->pageTitle = Yii::app()->name . ' - Статус займа';
} else {
	$this->pageTitle = Yii::app()->name . ' - Ваш Пакет займов';
}


// если можно оформить новый пакет
?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<h4>Статус займа</h4>
<?php endif; ?>
<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<h4>Ваш Пакет займов</h4>
<?php endif; ?>

<?php if (SiteParams::getIsIvanovoSite()): ?>
	<h5>Нет активных займов</h5>
<?php endif; ?>
<?php if (!SiteParams::getIsIvanovoSite()): ?>
	<h5>Нет активных Пакетов</h5>
<?php endif; ?>



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

<div class="clearfix"></div><br />
<?= $passFormRender // отображаем форму запроса SMS-пароля ?>
