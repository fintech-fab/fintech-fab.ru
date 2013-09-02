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
		'iDoneSteps' => 8,
	)); ?>

	<div class="span12">
		<h3>Ваша заявка отправлена!</h3>

		<p>
			Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8-800-555-75-78! </p>
	</div>

</div>


