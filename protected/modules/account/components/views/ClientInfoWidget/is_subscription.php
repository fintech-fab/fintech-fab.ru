<?php
/**
 * @var ClientInfoWidget $this
 */
?>
	<h4><?= $this->getHeader(); ?></h4>

<? $this->renderBalance(); ?>

<? $this->renderProduct(); ?>

<? $this->renderChannel(); ?>

<? $this->renderStatus(); ?>

<? $this->renderLoanPayDate(); ?>

<? $this->renderAvailableLoans(); ?>