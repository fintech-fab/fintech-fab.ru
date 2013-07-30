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
'action' => '/form/',
));
?>

<div class="container">
<div class="row span12">
	<h2>Контактные данные</h2>
		<? require dirname(__FILE__) . '/fields/contacts.php' ?>
</div>

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

<?

$this->endWidget();
?>
</div>
