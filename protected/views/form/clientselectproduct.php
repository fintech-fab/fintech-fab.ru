<?php
/* @var FormController $this*/
/* @var ClientSelectProductForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
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
		<img src="/static/img/01T.png"/>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class"=>"all"));
	?>
</div>
	<div class="row span5 hide conditions">
		<img src="/static/img/00T.png"/>
		<ul>
			<li>Сумма займа: <span class="cost final_price">3000</span> рублей</li>
			<li>Вернуть <span class="cost final_price">3000</span> рублей до: <span class="cost time">23:50</span>, <span class="cost date">среды, 7 августа 2013</span></li>
			<li>Стоимость подписки: <span class="cost price_count">350</span> рублей</li>
			<li>Срок подписки: <span class="cost price_month">30 дней</span></li>
			<li>Количество займов по подписке: <span class="cost count_subscribe">2 займа</span></li>
		</ul>
	</div>

	<div class="span2 hide picconditions"><img src="/static/img/step1.png"></div>

<div class="clearfix"></div>

	<div class="form-actions">
		<? $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => 'Далее →',
		)); ?>
	</div>
<?

$this->endWidget();
?>
