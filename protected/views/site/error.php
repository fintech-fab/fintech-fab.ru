<?php
/* @var $this SiteController */
/* @var $error array */
/* @var $code */

$this->pageTitle = Yii::app()->name . ' - Ошибка';
$this->breadcrumbs = array(
	'Ошибка',
);

$this->showTopPageWidget = true;
?>
<h2>Ошибка <?= $code; ?></h2>
<div class="error">
	<h5>Произошла техническая ошибка. Попробуйте повторить операцию или позвоните на горячую линию.</h5>
	<h5>Наши специалисты уже получили сообщение об ошибке и скоро ее исправят.</h5>
</div>
