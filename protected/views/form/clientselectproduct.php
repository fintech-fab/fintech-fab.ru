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
'action' => Yii::app()->createUrl('/form/'),
));
?>

<div class="row">

		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
		)); ?>

	<div class="row span5">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/01T.png"/>
		<?php
		$oClientCreateForm->product = "1";
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class"=>"all"));
	?>
	</div>
	<div class="row span5 conditions">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/00T.png"/>
		<ul>
			<li>Сумма займа: <span class="cost final_price"></span> рублей</li>
			<li>Вернуть <span class="cost final_price"></span> рублей до: <span class="cost time">23:50</span>, <span class="cost date"></span></li>
			<li>Стоимость подписки: <span class="cost price_count"></span> рублей</li>
			<li>Срок подписки: <span class="cost price_month"></span></li>
			<li>Количество займов по подписке: <span class="cost count_subscribe"></span></li>
		</ul>
	</div>

	<div class="span2 picconditions"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/step1.png"></div>

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
