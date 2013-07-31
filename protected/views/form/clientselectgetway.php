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
	'action' => '/form/',
));
?>
<div class="row">
	<div class="span12">
		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
		)); ?>
	</div>

	<div class="row span5">
		<img src="/static/img/02T.png">
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'get_way', Dictionaries::aWays(Yii::app()->session['product']));//TODO: вынести в контроллер
		?>
	</div>

	<div class="row span5 conditions" >
		<img src="/static/img/00T.png"/>
		<ul>
			<li>Сумма займа: <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[Yii::app()->session['product']]?></span> рублей</li>
			<li>Вернуть <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[Yii::app()->session['product']]?></span> рублей до: <span class="cost time">23:50</span>, <span class="cost date" data-time="<?php echo Dictionaries::$aDataTimes[Yii::app()->session['product']]?>">среды, 7 августа 2013</span></li>
			<li>Стоимость подписки: <span class="cost price_count"><?php echo Dictionaries::$aDataPrices[Yii::app()->session['product']]?></span> рублей</li>
			<li>Срок подписки: <span class="cost price_month"><?php echo Dictionaries::$aDataPriceCounts[Yii::app()->session['product']]?></span></li>
			<li>Количество займов по подписке: <span class="cost count_subscribe"><?php echo Dictionaries::$aDataCounts[Yii::app()->session['product']]?></span></li>
		</ul>
	</div>

	<div class="span2"><img src="/static/img/step1.png"></div>

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
