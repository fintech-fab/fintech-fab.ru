<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var string $sRedirectUri */

$this->pageTitle = Yii::app()->name;

// перенаправляем на следующую страницу....
Yii::app()->clientScript->registerMetaTag("3;url={$sRedirectUri}", null, 'refresh');
?>

<div class="row">

	<div class="span12">
		<div class="alert in alert-block fade alert-success"><strong>Вы успешно зарегистрировались в системе. </strong>
		</div>

		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label' => 'Перейти в личный кабинет »',
				'type'  => 'primary',
				'url' => Yii::app()->createUrl('/account/doSubscribe'),
			)
		); ?>
	</div>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps' => 3, //TODO: изменить
	)); ?>

</div>


