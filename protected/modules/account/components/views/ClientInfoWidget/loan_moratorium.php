<?php
/**
 * @var ClientInfoWidget $this
 */
?>
	<h4><?= $this->getHeader(); ?></h4>

<? $this->renderStatus(); ?>

<? $this->renderAvailableLoans(); ?>

<? $this->renderLoanAvailableDate(); ?>