<?php
/**
 * @var LoanWidget $this
 */
?>
<h4><?= $this->getHeader(); ?></h4>

<div class="form" id="activeForm">
	<div class="row">

		<div class="alert in alert-info">
			<?= Yii::app()->adminKreddyApi->getDoLoanMessage() ?>
		</div>

	</div>
</div>
