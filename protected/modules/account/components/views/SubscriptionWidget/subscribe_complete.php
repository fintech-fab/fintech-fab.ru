<?php
/**
 * @var SubscriptionWidget $this
 */
?>
<h4><?= $this->getHeader() ?></h4>

<div class="form" id="activeForm">
	<div class="row">

		<div class="alert in alert-info">
			<?= Yii::app()->adminKreddyApi->getDoSubscribeMessage() ?>
		</div>

	</div>
</div>
