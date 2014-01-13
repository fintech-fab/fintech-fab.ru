<?php
/**
 * @var $this ShowChannelsWidget
 */
?>

<div class="well center">
	<?= $this->getImage($this::C_CARD); ?>


	<?php // если карта не привязана, выводим сообщение о необходимости привязки
	if (!Yii::app()->adminKreddyApi->getIsClientCardExists()): ?>

		<?= $this->getNotAvailableChannelButton($this::C_CARD); ?>

		<br /><br />

		<?= preg_replace('/(внимание!)/ui', '<b style="color: #ff0000;">$1</b>', AdminKreddyApiComponent::C_CARD_WARNING_NO_CARD); ?>

		<br /><br />

		<?php $this->widget('bootstrap.widgets.TbButton',
			array(
				'url'         => Yii::app()->createUrl('account/addCard'),
				'type'        => 'danger',
				'label'       => 'Привязать карту',
				'htmlOptions' => array(
					'style' => 'width: ' . $this::BTN_WIDTH_PX . "px",
				),
			)
		);

	else: ?>
		<?= $this->getAvailableChannelSubmitButton($this::C_CARD); ?>
	<?php endif; ?>
</div>

<?php

if ($this->getIsChannelAvailable($this::C_MOBILE)) : ?>
	<div class="well center">
		<?= $this->getImage($this::C_MOBILE); ?>

		<?= $this->getAvailableChannelSubmitButton($this::C_MOBILE, $this::MSG_CONFIRM_CHANNEL_PHONE); ?>
	</div>

<?php endif; ?>
