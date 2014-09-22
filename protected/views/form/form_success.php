<?php
/**
 * Отображается страница "Вы успешно зарегистрировались в системе."
 *
 * Клиент попадает на страницу после заполнения анкеты полностью.
 *
 */
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var string $sRedirectUri */
/* @var string $sSuccessYmGoal */

$this->pageTitle = Yii::app()->name;

// перенаправляем на следующую страницу....
Yii::app()->clientScript->registerMetaTag("3;url={$sRedirectUri}", null, 'refresh');
?>

<? $this->widget('ext.pixels.PixelWidget') ?>

<div class="row">

	<div class="span12">
		<div class="alert in alert-block fade alert-success"><strong>Регистрация в системе прошла успешно!</strong>
		</div>

		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label' => 'Перейти в личный кабинет »',
				'type'  => 'primary',
				'url'   => Yii::app()->createUrl('/account/doSubscribe'),
			)
		); ?>
	</div>

</div>


