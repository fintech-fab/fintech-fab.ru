<?php
/* @var FormController $this */
/* @var $sSubView */

$this->pageTitle = Yii::app()->name;
?>

<div class="row">
	<?php
	$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

	$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs));
	?>
</div>

<?php $this->widget('FormSelectProductWidget'); ?>

<div id="formBody">
	<?php
	$model = Pages::model()->findByAttributes(array('page_name' => 'infographic'));
	if ($model) {
		echo $model->page_content;
	}
	?>
</div>

<div class="clearfix"></div>
<div class="row span10">
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'     => 'nextForm',
		'action' => Yii::app()->createUrl('/form/'),
	)); ?>

	<div class="form-actions">
		<div class="row">
			<div class="span2">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'    => 'backButton',
					'url'   => Yii::app()
							->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
					'label' => SiteParams::C_BUTTON_LABEL_BACK,
				)); ?>
			</div>

			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'nextButton',
				'buttonType' => 'submit',
				'url'   => Yii::app()
						->createUrl('/form/'),
				'type'  => 'primary',
				'label' => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>
