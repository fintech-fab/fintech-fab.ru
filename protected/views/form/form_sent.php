<?php
/* @var FormController $this */
/* @var ClientSendForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */

$this->pageTitle = Yii::app()->name;

?>

<div class="row">

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => 'sms',
	)); ?>

	<div class="span12">
		<div class="alert in alert-block fade alert-info"><strong>Вы успешно зарегистрировались в системе. </strong>
			Ожидайте результата по SMS. Если у Вас есть вопросы - позвоните нам 8-800-555-75-78!
		</div>
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label' => 'Перейти в личный кабинет »',
				'type'  => 'primary',
				'url'   => Yii::app()->createUrl('/account/subscribe'),
			)
		); ?>
	</div>

</div>


