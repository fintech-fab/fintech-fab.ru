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

	<div class="span12">
		<div class="row">
			<ul style="list-style: none outside none; margin-left: 0; margin-right: 20px;">
				<li style="display: inline-block; width: 20%"><?= CHtml::link('Выбор пакета', array("form/1")); ?></li>


				<li style="display: inline-block; width: 20%"><?= CHtml::link('Личные данные', array("form/2")); ?></li>


				<li style="display: inline-block; width: 20%"><a class="active">Паспортные данные</a></li>

				<li style="display: inline-block; width: 20%">Адрес</li>

				<li style="display: inline-block; width: 20%">Отправка заявки</li>
			</ul>
		</div>
		<div class="row">
			<?php $this->widget('bootstrap.widgets.TbProgress', array(
				'type'        => 'danger', // 'info', 'success' or 'danger'
				'percent'     => 80, // the progress
				'striped'     => true,
				'animated'    => true,
				'htmlOptions' => array(
					'style' => 'height: 10px; margin-right: 20px;',
				),
			)); ?>
		</div>
	</div>

	<div class="clearfix"></div>
	<div id="formBody">
		<?php $this->renderPartial($sSubView, array('oClientCreateForm' => $oClientCreateForm)) ?>
	</div>

<?php
$this->widget('YaMetrikaGoalsWidget', array(
	'iDoneSteps' => Yii::app()->clientForm->getCurrentStep()
));
