<?php
/**
 * @var AccountMenuWidget $this
 */

?>

<div class="well in">
	<?php $this->widget('application.modules.account.components.SessionExpireTimeWidget'); ?>
</div>

<div class="well" style="padding: 8px; 0; margin-top: 20px;">
	<?php

	$this->beginWidget('bootstrap.widgets.TbMenu', array(
		'type'          => 'list', // '', 'tabs', 'pills' (or 'list')
		'stacked'       => true, // whether this is a stacked menu
		'items'         => $this->aMenu,
		'activateItems' => true,
		'htmlOptions'   => array('style' => 'margin-bottom: 0;'),
	));
	?>

	<div style="padding-left: 20px;">
		<h4><?= Yii::app()->adminKreddyApi->getClientFullName(); ?></h4>

		<?php if (Yii::app()->adminKreddyApi->getBankCardPan()) { ?>
			<p>
				<strong>Банковская карта:</strong>    <?= Yii::app()->adminKreddyApi->getBankCardPan() ?>
			</p>
		<?php } ?>
		<p>
			<? $this->renderBalanceInfo(); ?>
		</p></div>
	<?php $this->endWidget(); ?>

</div>
