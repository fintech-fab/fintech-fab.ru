<?php
/* @var FormController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>
<div class="row">
	<?php //$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>
	<?php
	if (Yii::app()->clientForm->hasError()) {
		?>
		<div class="alert alert-error"><?= Yii::app()->clientForm->getError(); ?></div>
	<?php } ?>


	<?php $this->widget('FastRegProductsWidget', array('oClientCreateForm' => $oClientCreateForm)); ?>

	<div class="clearfix"></div>
	<br />

	<div class="span8 offset2">
		<div class="alert in alert-block fade alert-info center">
			<strong>Если Вы уже являетесь нашим Клиентом, воспользуйтесь <?=
				CHtml::link('Личным кабинетом', Yii::app()
					->createUrl('account')) ?>.</strong>
		</div>
	</div>
</div>
<script lang="javascript">
	/*$('#tabs a').click(function (e) {
	 e.preventDefault();
	 $(this).tab('show');
	 })*/

</script>