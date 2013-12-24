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

//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
//сделано во избежание навешивания кучи эвентов
Yii::app()->clientScript->registerScript('ajaxForm', '
		updateAjaxForm();
		');
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php
//TODO сделать getProgressBarStep()
$this->widget('FormProgressBarWidget', array('aSteps' => SiteParams::$aFormWidgetSteps, 'iCurrentStep' => (Yii::app()->clientForm->getCurrentStep() - 1)));
?>
	<h4>Отправка заявки</h4>
	<div class="span5">
		<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', SiteParams::getHintHtmlOptions($oClientCreateForm, 'numeric_code')); ?>
		<?= $form->dropDownListRow2($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, SiteParams::getHintHtmlOptions($oClientCreateForm, 'secret_question')); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', SiteParams::getHintHtmlOptions($oClientCreateForm, 'secret_answer')); ?>
	</div>
<div class="span5 offset1">
	<?= $form->passwordFieldRow($oClientCreateForm, 'password', SiteParams::getHintHtmlOptions($oClientCreateForm, 'password') + array('autocomplete' => 'off')); ?>
	<?= $form->passwordFieldRow($oClientCreateForm, 'password_repeat', SiteParams::getHintHtmlOptions($oClientCreateForm, 'password_repeat') + array('autocomplete' => 'off')); ?>
</div>
	<div class="clearfix"></div>
	<div class="row span10">
		<div class="form-actions">
			<div class="row">
				<div class="span1">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'id'          => 'backButton',
						'buttonType'  => 'ajaxButton',
						'ajaxOptions' => array(
							'update' => '#formBody',
						),
						'url'         => Yii::app()
								->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
						'label'       => 'Назад',
					)); ?>
				</div>

				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'buttonType'  => 'ajaxSubmit',
					'ajaxOptions' => array(
						'type'     => 'POST',
						'update'   => '#formBody',
					),
					'url'         => Yii::app()->createUrl('/form/ajaxForm'),
					'type'       => 'primary',
					'label'      => 'Далее',
				)); ?>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
