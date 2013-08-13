<?php
/* @var FormController $this */
/* @var InviteToIdentificationForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Ваша заявка отправлена!
 * Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
 * Предлагаем дополнительно пройти видеорегистрацию
 */


$this->pageTitle = Yii::app()->name;

?>
<div class="row">

	<?php $this->widget('CheckBrowserWidget', array(
		'sMessage'     => "<strong>Внимание!</strong> Для того чтобы пройти
видеоидентификацию, Вам нужен браузер <strong>Chrome</strong> или <strong>Firefox</strong>
последних версий.",
		'aHtmlOptions' => array(
			'style' => 'font-size: 15px;',
		)
	)); ?>

	<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<div class="row span12">
		<h4 class="center">
			Выберите способ идентификации личности </h4>
	</div>
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'     => get_class($oClientCreateForm),
		'action' => Yii::app()->createUrl('/form/'),
	)); ?>
	<div class="row">
		<div class="span5"><h4 class="center">Видеоидентификация</h4>

			<iframe src="<?php echo Yii::app()
				->createUrl('/form/checkwebcam'); ?>" width="400px" height="313px" frameborder="no" scrolling="no"></iframe>

			<?php echo $form->hiddenField($oClientCreateForm, 'go_identification'); ?>
			<div class="clearfix"></div>
			<div class="form-actions">
				<? $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'  => 'button',
					'type'        => 'primary',
					'label'       => 'Пройти видеоидентификацию на сайте →',
					'htmlOptions' => array(
						'onclick' => 'js:{
					    $("#' . get_class($oClientCreateForm) . '_go_identification").val("1");
					    $("#' . get_class($oClientCreateForm) . '").submit();
					    yaCounter21390544.reachGoal("identification_video");
					  }'
					)
				)); ?>
			</div>
			<br />

			<p>Необходим компьютер с веб-камерой. Вы последовательно отправляете нам оригиналы документов через веб-камеру, и, не
				выходя из дома, получаете решение по займу.</p>
		</div>
		<div class="span5 offset1">

			<h4 class="center">Идентификация в офисе Кредди</h4>
			<a data-target="#fl-contacts" data-toggle="modal" href="#fl-contacts">
				<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/map-plan.png" alt="Карта" title="Карта" />
			</a>

			<p>Город Москва, шоссе Энтузиастов 12, корп. 2 <br />ТЦ Город, главный вход, первый этаж</p>

			<p>Отделение идентификации сервиса
				Кредди:<br /><a data-target="#fl-contacts" data-toggle="modal" href="#fl-contacts">узнать подробное
					местоположение</a></p>

			<div class="clearfix"></div>
			<div class="form-actions">
				<? $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'  => 'button',
					'type'        => 'primary',
					'label'       => 'Пройти идентификацию в отделении →',
					'htmlOptions' => array(
						'onclick' => 'js: {
							$("#' . get_class($oClientCreateForm) . '_go_identification").val("2");
                            $("#' . get_class($oClientCreateForm) . '").submit();
                            yaCounter21390544.reachGoal("identification_offline");
					}'
					)
				)); ?>
			</div>
			<br />

			<p>Необходимо подъехать на стойку идентификации сервиса Кредди с паспортом и вторым документом.</p>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<?


?>
