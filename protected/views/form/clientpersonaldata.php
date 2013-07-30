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
'action' => '/form',
));
?>

<h2>Контактные данные</h2>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/contacts.php' ?>
</div>

<h2>Личные данные</h2>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/name.php' ?>
	<? require dirname(__FILE__) . '/fields/personal_info.php' ?>
</div>

<h2>Второй документ</h2>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/document.php' ?>
</div>
