<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";

// временно недоступно
?>
<h4>Подключение Пакета</h4>

<div class="alert alert-error">
	У Вас нет доступных каналов для получения займа! Требуется привязать к аккаунту банковскую карту.
</div>
<div class="clearfix"></div>
<div class="form-actions">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'label' => 'Привязать банковскую карту', 'icon' => "icon-ok icon-white", 'type' => 'primary', 'size' => 'large', 'url' => Yii::app()
				->createUrl('account/addCard'),
	));
	?>
</div>
