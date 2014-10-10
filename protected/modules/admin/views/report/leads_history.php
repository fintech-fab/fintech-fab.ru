<?php
/* @var $this ReportController */
/* @var $dataProvider CActiveDataProvider */
/* @var CActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Лиды";

?>

	<h1>Лиды</h1>

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'                     => 'lead-report-form',
	'enableClientValidation' => true,
));
?>

	<label>с: <input type="date" name="dateFrom" value="" class="input-medium" /></label>
	<label>до: <input type="date" name="dateTo" value="" class="input-medium" /></label>
	<br />
	<button class="btn btn-primary">Загрузить</button>

<?php
$this->endWidget();
?>