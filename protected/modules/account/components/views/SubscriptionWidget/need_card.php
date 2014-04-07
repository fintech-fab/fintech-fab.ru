<?php
/**
 * @var SubscriptionWidget $this
 * @var IkTbActiveForm     $form
 */


?>
<h4><?= $this->getHeader(); ?></h4>

<div class="alert alert-error">
	<?= $this->getNeedBankCardMessage() ?>
</div>
<div class="clearfix"></div>
<div class="form-actions">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'label' => $this->getAddBankCardButtonLabel(),
		'icon'  => '"icon-ok icon-white',
		'type'  => 'primary',
		'size'  => 'large',
		'url'   => Yii::app()->createUrl('account/addCard'),
	));
	?>
</div>
