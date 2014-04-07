<?php
/**
 * @var LoanWidget $this
 */
?>
<h4><?= $this->getHeader(); ?></h4>


<div class="alert alert-error"><?= Yii::app()->adminKreddyApi->getLoanNotAvailableMessage() ?></div>
