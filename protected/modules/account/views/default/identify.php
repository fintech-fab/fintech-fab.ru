<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Идентификация";

Yii::app()->clientScript->registerScript('goIdentify', '
	//по нажатию кнопки отправляем эвент ajax-ом, затем сабмитим форму
	function goIdentify(type)
	{
		$.ajax({url: "/account/goIdentify"}).done(function() {
			$("#identify-form").find("input[name=type]").val(type);
			$("#identify-form").submit();
		});
	}
	', CClientScript::POS_HEAD);
?>
<h4>Идентификация</h4>

<?php if (!Yii::app()->adminKreddyApi->isFirstIdentification()): ?>
	<p>Уважаемый Клиент, идентификация - это процедура подтверждения Вашей личности.</p>
	<p>После идентификации потребуется ввести данные документов, использованных при идентификации.</p>
<?php endif; ?>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'identify-form',
	'action'               => $model->video_url,
	'method'               => 'post',
	'enableAjaxValidation' => false,
	'clientOptions'        => array(
		'validateOnSubmit' => false,
	),
));
?>
<?= $form->hiddenField($model, 'type', array('name' => 'type')); ?>
<?= $form->hiddenField($model, 'client_code', array('name' => 'client_code')); ?>
<?= $form->hiddenField($model, 'service', array('name' => 'service')); ?>
<?= $form->hiddenField($model, 'signature', array('name' => 'signature')); ?>
<?= $form->hiddenField($model, 'timestamp', array('name' => 'timestamp')); ?>
<?= $form->hiddenField($model, 'redirect_back_url', array('name' => 'redirect_back_url')); ?>

<div class="row">
	<div class="span4">
		<div class="alert in alert-block alert-info" style="height: 135px;">
			<h4>Видеоидентификация</h4>
			Нужна веб-камера и браузер Chrome или Firefox.<br /> <a href="<?=
			Yii::app()
				->createUrl('/pages/view/browser') ?>" target="_blank">Скачать браузер >>></a> <br /> <br />
			<?php $this->widget("CheckBrowserWidget"); ?>

			<div class="center">
				<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'type'        => 'primary',
					'size'        => 'large',
					'label'       => 'Пройти идентификацию',
					'htmlOptions' => array(
						'onclick' => 'js: goIdentify(1)'
					)
				));
				?>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="alert in alert-block alert-info" style="height: 135px;">
			<h4>Идентификация с загрузкой фото</h4>
			Нужен фотоаппарат или мобильный телефон с фотокамерой <br /><br />

			<div class="center">
				<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'type'        => 'primary',
					'size'        => 'large',
					'label'       => 'Пройти идентификацию',
					'htmlOptions' => array(
						'onclick' => 'js: goIdentify(2)'
					)
				));
				?>
			</div>
		</div>
	</div>
</div>
<div class="alert in alert-block alert-info">
	<h4>Идентификация на смартфоне</h4>
	Нужен фотоаппарат или мобильный телефон с фотокамерой<br /><br />

	<div class="fright">
		<img width="180" height="180" src="/static/images/app_google_qr_180.png" />
	</div>

	<br /><a href="https://play.google.com/store/apps/details?id=ru.kreddy" target="_blank">
		<img alt="Get it on Google Play" src="/static/images/ru_generic_rgb_wo_45.png" /> </a> <br /><br />Приложение
	доступно для смартфонов и планшетов с платформой Android (Samsung, HTC, Sony, Alcatel и другие).

	<br class="cl" />

	<br />

</div>
<?php
$this->endWidget();

?>
