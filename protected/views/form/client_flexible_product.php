<?php
/* @var FormController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Заявка на заём', 2),
	array('Подтверждение номера телефона', 3)
);

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/products.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);

?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>
	<?php

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'clientOptions'        => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'               => Yii::app()->createUrl('/form/'),
	));

	?>
	<div class="row span7">
		<?php $this->widget('SliderWidget',array('form'=>$form)); ?>
	</div>

	<div class="row offset1 span4 conditions">
		<h5 class="pay_legend">Выбранные условия</h5>
		<?php

		?>
		<ul>
			<li>Размер займа:
				<span class="cost final_price"><?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
			</li>
			<li>Вернуть <span class="cost final_price">
				<?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
				до:&nbsp;<span class="cost time">23:50</span>, <span class="cost date"><?= "";//$getDateToPayUntil; ?></span>
			</li>
			<li>Стоимость подключения:
				<span class="cost price_count"><?= "";//Dictionaries::$aDataPrices[$this->chosenProduct] ?></span>&nbsp;рублей
			</li>
			<li>Срок подключения:
				<span class="cost price_month"><?= "";//Dictionaries::$aDataPriceCounts[$this->chosenProduct] ?></span></li>
			<li>Размер Пакета:
				<span class="cost packet_size"><?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>
			</li>
			<li>Количество займов в Пакете:
				<span class="cost count_subscribe"><?= "";//Dictionaries::$aDataCounts[$this->chosenProduct] ?></span></li>
		</ul>
	</div>

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее →',
			)); ?>
		</div>
	</div>
	<?php

	$this->endWidget();

	?>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => Yii::app()->clientForm->getCurrentStep(),
	)); ?>

</div>
