<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var $sSubView */

$this->pageTitle = Yii::app()->name;
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php if (Yii::app()->clientForm->hasError()) { ?>
	<div class="alert alert-error"><?= Yii::app()->clientForm->getError(); ?></div>
<?php } ?>
	<div id="formBody">
		<?php $this->renderPartial($sSubView, array('oClientCreateForm' => $oClientCreateForm)) ?>
	</div>
<?php

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/ajax_form.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_BEGIN);
