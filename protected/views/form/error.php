<?php
/**
 * Отображается страница "Произошла неизвестная ошибка"
 *
 */
/* @var FormController $this */

$this->pageTitle = Yii::app()->name;

?>

<?php $this->widget('YaMetrikaGoalsWidget', array('sForceGoal' => 'form_error')); ?>

<div class="row">

	<div class="span12">
		<?php if (Yii::app()->clientForm->hasError()) { ?>
			<div class="alert alert-error"><?= Yii::app()->clientForm->getError(); ?></div>
		<?php } ?>

		<div class="center">
			<?php $this->widget(
				'bootstrap.widgets.TbButton',
				array(
					'label' => 'Вернуться на сайт',
					'type'  => 'primary',
					'url'   => Yii::app()->createUrl('/form'),
				)
			); ?>
		</div>
	</div>

</div>


