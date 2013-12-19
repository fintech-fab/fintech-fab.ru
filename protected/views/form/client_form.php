<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var $sSubView */

$this->pageTitle = Yii::app()->name;


$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

<?php if (Yii::app()->session['error']): ?>
	<div class="alert alert-error"><?= Yii::app()->session['error']; ?></div>
	<?php Yii::app()->session['error'] = null; ?>
<?php endif; ?>
	<div class="clearfix"></div>
	<script type="text/javascript">
		$('#second-document-popover').popover({
			html: true,
			trigger: 'hover',
			content: 'Заграничный паспорт<br/>Водительское удостоверение<br/>'
				+ 'Пенсионное удостоверение<br/>Военный билет<br/>Свидетельство ИНН<br/>'
				+ 'Страховое свидетельство государственного пенсионного страхования'
		});
	</script>

	<div id="formBody">
	<?php $this->renderPartial($sSubView, array('oClientCreateForm' => $oClientCreateForm)) ?>
	</div>

<?php
$this->widget('YaMetrikaGoalsWidget', array(
	'iDoneSteps' => Yii::app()->clientForm->getCurrentStep()
));
