<?php
/**
 * Отображается страница "Вы успешно зарегистрировались в системе."
 *
 * Клиент попадает на страницу после быстрой регистрации
 *
 */
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

$this->pageTitle = Yii::app()->name;

?>

<?php $this->widget('YaMetrikaGoalsWidget', array('sForceGoal' => 'client_exists')); ?>

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


