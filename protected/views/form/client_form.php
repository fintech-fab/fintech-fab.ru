<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var $sSubView */

$this->pageTitle = Yii::app()->name;
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

	<div class="row">
		<?php
		$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

		$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs));
		?>
	</div>
<?php $this->widget('FormSelectProductWidget'); ?>

<?php if (Yii::app()->session['error']): ?>
	<div class="alert alert-error"><?= Yii::app()->session['error']; ?></div>
	<?php Yii::app()->session['error'] = null; ?>
<?php endif; ?>
	<div id="formBody">
		<?php $this->renderPartial($sSubView, array('oClientCreateForm' => $oClientCreateForm)) ?>
	</div>
<?php

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/ajax_form.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_BEGIN);
