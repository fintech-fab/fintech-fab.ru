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
			<?= $form->textFieldRow($oClientCreateForm, 'last_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'last_name') + array('disabled' => 'disabled')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'first_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'first_name') + array('disabled' => 'disabled')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'third_name', SiteParams::getHintHtmlOptions($oClientCreateForm, 'third_name') + array('disabled' => 'disabled')); ?>
			<?= $form->dateMaskedRow($oClientCreateForm, 'birthday', SiteParams::getHintHtmlOptions($oClientCreateForm, 'birthday') + array('size' => '5', 'class' => 'inline')); ?>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', SiteParams::getHintHtmlOptions($oClientCreateForm, 'phone') + array('size' => '15', 'disabled' => 'disabled')); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'email', SiteParams::getHintHtmlOptions($oClientCreateForm, 'email') + array('disabled' => 'disabled')); ?>
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
		$oClientCreateForm->agree = false;
		echo $form->checkBoxRow($oClientCreateForm, 'agree');
		?>
	</div>
</div>
<div class="clearfix"></div>
<div class="span12 ">
	<div class="form-actions row">
		<div class="span2"></div>

		<div class="span2 offset2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'          => 'submitButton',
				'buttonType'  => 'ajaxSubmit',
				'ajaxOptions' => array(
					'complete' => 'checkBlankResponse',
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
