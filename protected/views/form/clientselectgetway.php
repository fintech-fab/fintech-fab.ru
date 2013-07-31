<?php
/* @var FormController $this*/
/* @var ClientSelectGetWayForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор способа получения займа
 */


$this->pageTitle=Yii::app()->name;

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id' => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'action' => Yii::app()->createUrl('/form/'),
));
?>
<div class="row">
		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
		)); ?>

	<div class="row span5">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/02T.png">
		<?php
		$oClientCreateForm->get_way = "1";
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'get_way', Dictionaries::aWays(Yii::app()->session['product']));//TODO: вынести в контроллер
		?>
	</div>

	<div class="row span5 conditions" >
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/00T.png"/>
		<?php
			$n = Dictionaries::$aDataTimes[Yii::app()->session['product']];
			$d = new DateTime('now');
			$d->add(new DateInterval('P'.$n.'D'));
			$getDateToPayUntil = Dictionaries::$aDays[$d->format('w')].", ".$d->format('j')." ".Dictionaries::$aMonths[$d->format('n')]." ".$d->format('Y');
		?>
		<ul>
			<li>Сумма займа: <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[Yii::app()->session['product']]?></span> рублей</li>
			<li>Вернуть <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[Yii::app()->session['product']]?></span> рублей до: <span class="cost time">23:50</span>, <span class="cost date"><?php echo $getDateToPayUntil; ?></span></li>
			<li>Стоимость подписки: <span class="cost price_count"><?php echo Dictionaries::$aDataPrices[Yii::app()->session['product']]?></span> рублей</li>
			<li>Срок подписки: <span class="cost price_month"><?php echo Dictionaries::$aDataPriceCounts[Yii::app()->session['product']]?></span></li>
			<li>Количество займов по подписке: <span class="cost count_subscribe"><?php echo Dictionaries::$aDataCounts[Yii::app()->session['product']]?></span></li>
		</ul>
	</div>

	<div class="span2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/step2.png"></div>

	<div class="clearfix"></div>

	<div class="form-actions">
		<? $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => 'Далее →',
		)); ?>
	</div>
</div>
	<?

	$this->endWidget();
	?>
