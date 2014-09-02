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
/* @var string $sRedirectUri */
/* @var string $sSuccessYmGoal */

//$this->pageTitle = Yii::app()->name;

// перенаправляем на следующую страницу....
Yii::app()->clientScript->registerMetaTag("3;url={$sRedirectUri}", null, 'refresh');
?>

<?php $this->widget('YaMetrikaGoalsWidget', array('sForceGoal' => $sSuccessYmGoal)); ?>
<div class="container" style="margin-top: 20px; margin-bottom: 20px; height: 500px;">
	<div class="row">
		<div class="col-xs-8">
			<div class="alert in alert-block fade alert-success"><strong>Ты успешно зарегистрировался в системе. </strong>
			</div>

			<?php $this->widget(
				'bootstrap.widgets.TbButton',
				array(
					'label' => 'Перейти в личный кабинет »',
					'type'  => 'primary',
					'type'  => 'success',
					'url'   => Yii::app()->createUrl('/account/doSubscribe'),
				)
			); ?>
		</div>

	</div>
</div>