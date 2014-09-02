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

<?php $this->widget('YaMetrikaGoalsWidget', array('sForceGoal' => 'fr_client_exists')); ?>
<div class="container" style="margin-top: 20px; margin-bottom: 20px; height: 500px;">
<div class="row">

	<div class="col-xs-8">
		<div class="center"><h3>Мы уже знаем о тебе!</h3></div>
		<h4><strong>Если ты регистрировался ранее, выполни вход в личный кабинет.</strong></h4>
		<h4><strong>В противном случае обратить в наш контактный центр.</strong></h4>        <br /> <br />

		<div class="center">
			<?php $this->widget(
				'bootstrap.widgets.TbButton',
				array(
					'label' => 'Вернуться на сайт',
					'type'  => 'primary',
					'url'   => Yii::app()->createUrl('/form'),
				)
			); ?>&nbsp;
			<?php $this->widget(
				'bootstrap.widgets.TbButton',
				array(
					'label' => 'Перейти в личный кабинет',
					'type'  => 'success',
					'url'   => Yii::app()->createUrl('/account/login'),
				)
			); ?>
		</div>
	</div>

</div>


</div>