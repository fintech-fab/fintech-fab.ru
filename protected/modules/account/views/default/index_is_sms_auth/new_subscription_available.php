<?php
/**
 * @var $this DefaultController
 *
 */

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

<?php if (Yii::app()->adminKreddyApi->checkSubscribe()): ?>
	<div class="clearfix"></div>

	<br />
	<div class="well">
		<?php
		if (!SiteParams::getIsIvanovoSite()) {
			$sLabel = 'Подключить Пакет';
		} else {
			$sLabel = 'Оформить займ';
		}
		$this->widget('bootstrap.widgets.TbButton', array(
			'label' => $sLabel, 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'small', 'url' => Yii::app()
					->createUrl('account/subscribe'),
		));
		?>
	</div>
<?php endif; ?>
