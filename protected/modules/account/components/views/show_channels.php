<?php
/**
 * @var $this                 ShowChannelsWidget
 * @var $aChannelsIfAvailable array
 * @var $aChannelNames        array
 * @var $aImageNames          array
 */
?>

<div class="well center">
	<img src="/static/images/channels/<?= $aImageNames[ShowChannelsWidget::C_CARD] ?>" style="height:100px;"> &nbsp;

	<?php

	// если карта не привязана, выводим сообщение о необходимости привязки
	if (!Yii::app()->adminKreddyApi->getBankCardPan()
		|| ($aChannelsIfAvailable[ShowChannelsWidget::C_CARD] === false)
	) {
		echo $this->getNotAvailableChannelButton('на банковскую карту');

		echo '<br /><br />' . AdminKreddyApiComponent::C_CARD_WARNING_NO_CARD . "<br/><br/>";

		$this->widget('bootstrap.widgets.TbButton',
			array(
				'url'   => Yii::app()->createUrl('account/addCard'),
				'type'  => 'danger',
				'label' => 'Привязать карту',
			)
		);
	} else {
		echo $this->getAvailableChannelSubmitButton($aChannelNames[ShowChannelsWidget::C_CARD], $aChannelsIfAvailable[ShowChannelsWidget::C_CARD]);
	}
	?>
</div>

<?php if ($aChannelsIfAvailable[ShowChannelsWidget::C_MOBILE]) : ?>
	<div class="well center">
		<img src="/static/images/channels/<?= $aImageNames[ShowChannelsWidget::C_MOBILE] ?>" style="height:100px;">
		&nbsp;

		<?= $this->getAvailableChannelSubmitButton('на мобильный телефон', $aChannelsIfAvailable[ShowChannelsWidget::C_CARD], ShowChannelsWidget::MSG_CONFIRM_CHANNEL_PHONE); ?>
	</div>

<?php endif; ?>
