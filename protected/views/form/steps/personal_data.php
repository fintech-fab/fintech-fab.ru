<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

//TODO yaCounter21390544.reachGoal("expand_1");

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'hideErrorMessage' => true,
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form/'),
));

//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
//сделано во избежание навешивания кучи эвентов
Yii::app()->clientScript->registerScript('ajaxForm', '
		updateAjaxForm();
		');
Yii::app()->clientScript->registerScript('scrollAndFocus', '
		scrollAndFocus();
		', CClientScript::POS_LOAD);
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php
$this->widget('FormProgressBarWidget', array('aSteps' => Yii::app()->clientForm->getFormWidgetSteps(), 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
<div class="clearfix"></div><h4>Личные данные</h4>
<div class="row">
	<div class="span6">
		<div class="row">
			<?= $form->textFieldRow($oClientCreateForm, 'last_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'last_name')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'first_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'first_name')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'third_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'third_name')); ?>
			<?= $form->dateMaskedRow($oClientCreateForm, 'birthday', SiteParams::getHintHtmlOptions($oClientCreateForm, 'birthday') + array('size' => '5', 'class' => 'inline')); ?>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'phone') + array('size' => '15')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'email', SiteParams::getHintHtmlOptions($oClientCreateForm, 'email')); ?>
			<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
			<div id="sex">
				<?= $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes, array('uncheckValue' => '999')); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="span12">
		<?php
		$oClientCreateForm->complete = false;
		echo $form->checkBoxRow($oClientCreateForm, 'complete');
		?>
	</div>
</div>
<div class="clearfix"></div>
<div class="span12 ">
	<div class="form-actions row">
		<div class="span2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'url'   => Yii::app()->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label' => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>

		<div class="span2 offset2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'submitButton',
				'buttonType'  => 'ajaxSubmit',
				'ajaxOptions' => array(
					'type'   => 'POST',
					'update' => '#formBody',
				),
				'url'         => Yii::app()->createUrl('/form/ajaxForm'),
				'type'        => 'primary',
				'label'       => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
