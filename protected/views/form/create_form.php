<?php

/**
 * @var IkAdminController $this
 * @var IkTbActiveForm $form
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var string $sActionUrl
 */

if(!empty($oClient) && ikIsAccess('ClientFormUpdate')){
	$this->addToolbarActivity(
		'Карточка',
		$this->getLink('/client/view/id/' . $oClient->id . '/' ),
		'icon-eye-open'
	);
}

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id' => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'action' => $sActionUrl,
));

// точка ввода выбирается только если ее еще нет (при вводе анкеты)
if(!$oClientCreateForm->entry_point_id){
	?>
	<div class="row span12">
		<?php require dirname(__FILE__) . '/fields/entry_point.php'; ?>
	</div>
	<?
}
?>

<h2>Паспортные данные</h2>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/group_passport.php' ?>
</div>

<h2>Адрес регистрации</h2>
<div class="row span12">
	<? require dirname(__FILE__) . '/fields/address_reg.php' ?>
	<? require dirname(__FILE__) . '/fields/address_reg_details.php' ?>
	<? require dirname(__FILE__) . '/fields/address_res_toggle.php' ?>
</div>

<h2 class="layer-address_reg_as_res">Адрес проживания</h2>
<div class="row span12 alert alert-warning layer-address_reg_as_res">
	<? require dirname(__FILE__) . '/fields/address_res.php' ?>
	<? require dirname(__FILE__) . '/fields/address_res_details.php' ?>
</div>

<h2>Контактные данные</h2>
<div class="row span12">
	<span class="span2">
		<?=$form->phoneMaskedRow($oClientCreateForm, 'phone_home', array( 'class' => 'span2', 'mask'=>'8 999 999 99 99') ); ?>
	</span>
	<? require dirname(__FILE__) . '/fields/contacts.php' ?>
</div>

<h2>Второй документ</h2>
<div class="row span12">
	<? require dirname(__FILE__) . '/fields/document.php' ?>
</div>

<h2>Контактные данные ближайшего окружения</h2>
<div class="row span12">
	<? require dirname(__FILE__) . '/fields/relatives_one.php' ?>
</div>

<h2>О работе</h2>
<div class="row span12">
	<? require dirname(__FILE__) . '/fields/job.php' ?>
</div>

<h2>Рабочие контакты и доходы</h2>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/details_job_contacts.php' ?>
</div>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/details_job_money.php' ?>
</div>

<div class="row span12">
	<? require dirname(__FILE__) . '/fields/details_job_having.php' ?>
</div>

<? require dirname(__FILE__) . '/fields/details_contacts.php' ?>



<div class="clearfix"></div>

<div class="form-actions">
	<? $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type' => 'primary',
		'label' => 'сохранить',
	)); ?>
</div>

<?

$this->endWidget();