<?php
/* @var FormController $this*/
/* @var ClientPersonalDataForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Контактные данные:
 * + Телефон
 * + Электронная почта
 * Личные данные:
 * + Фамилия
 * + Имя
 * + Отчество
 * + Дата рождения
 * + Пол
 * Паспортные данные:
 * + Серия / номер
 * + Когда выдан
 * -+ Кем выдан
 * + Код подразделения
 * Второй документ:
 * + Название
 * + Номер
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
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/03T.png">
	<h2>Контактные данные</h2>
		<? require dirname(__FILE__) . '/fields/contacts.php' ?>
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

	<div class="span2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/step3.png"></div>

<div class="row span12">
	<div class="span5"><h2>Личные данные</h2>
		<? require dirname(__FILE__) . '/fields/name.php' ?>
		<? require dirname(__FILE__) . '/fields/personal_info.php' ?>
	</div>
	<div class="span5"><h2>Паспортные данные</h2>
		<? require dirname(__FILE__) . '/fields/passport.php' ?>
	</div>
</div>

<div class="row span12">
	<h2>Второй документ</h2>

	<? require dirname(__FILE__) . '/fields/document.php' ?>
</div>

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
