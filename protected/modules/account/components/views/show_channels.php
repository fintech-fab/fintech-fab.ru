<?php
/**
 * @var $this ShowChannelsWidget
 */
?>

<div class="well center">
	<?= $this->getCardImage(); ?>

	<?php if ($this->getIsCardAvailable()): ?>

		<?= $this->getCardSubmitButton(); ?>

	<?php else: ?>

		<?= $this->getCardNotAvailableButton(); ?>

		<br /><br />

		<?= $this->getNoCardWarning(); ?>

		<br /><br />

		<?= $this->getAddCardButton(); ?>

	<?php endif; ?>
</div>

<?php if ($this->getIsMobileAvailable()) : ?>

	<div class="well center">
		<?= $this->getMobileImage(); ?>

		<?= $this->getMobileSubmitButton(); ?>
	</div>

<?php endif; ?>
