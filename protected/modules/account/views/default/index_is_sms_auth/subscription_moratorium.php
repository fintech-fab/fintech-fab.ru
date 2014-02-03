<?php
/**
 * @var $this DefaultController
 *
 */

// Если есть мораторий на подписку
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

<div class="clearfix"></div>

<br />
<div class="well">
	Вы можете отправить новую заявку <?= Yii::app()->adminKreddyApi->getMoratoriumSubscriptionLoan() ?>
	<br />
</div>
