<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action' => Yii::app()->createUrl('/form'),
));

?>
<?php
$this->widget('FormProgressBarWidget', array('aSteps' => SiteParams::$aFormWidgetSteps, 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
	<h4>Отправка заявки</h4>
	<div class="span5">
		<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', array('class' => 'span3')); ?>
		<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span3')); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span3')); ?>
	</div>
	<div class="span6">
		<?= $form->passwordFieldRow($oClientCreateForm, 'password', array('autocomplete' => 'off')); ?>
		<?= $form->passwordFieldRow($oClientCreateForm, 'password_repeat', array('autocomplete' => 'off')); ?>
	</div>
	<div class="clearfix"></div>
	<div class="row span10">
		<div class="form-actions">
			<div class="row">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'buttonType'  => 'ajaxSubmit',
					'ajaxOptions' => array(
						'type'     => 'POST',
						'update'   => '#formBody',
						//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
						//сделано во избежание навешивания кучи эвентов
						'complete' => 'jQuery("body").off("click","#submitButton")',
					),
					'url'         => Yii::app()->createUrl('/form/ajaxForm'),
					'type'       => 'primary',
					'label'      => 'Далее',
				)); ?>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>