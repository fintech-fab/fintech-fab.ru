<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Payments';
$this->breadcrumbs=array(
	'Payments',
);
?>
<?php
$this->beginWidget('TopPageWidget');
$this->endWidget();
?>

<div class="container container_12">
	<div class="grid_12">
		<fieldset>
			<legend class="pay_legend">Выберите способ оплаты</legend>
			<div class="row">
				<a href="?r=site/page&view=elecsnet" class="pay_button pay_button_elecsnet"></a>
				<a href="?r=site/page&view=qiwi" class="pay_button pay_button_qiwi"></a>
				<a href="?r=site/page&view=cc" class="pay_button pay_button_acquiropay"></a>
				<!--
				<a href="?page=yandex" class="pay_button pay_button_yandex"></a>
				-->
			</div>
		</fieldset>
	</div>
</div>