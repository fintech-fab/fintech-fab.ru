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

$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Знакомство', 2),
	array('Заявка на займ', 5, 3)
);
?>
<div class="row">

	<?php $this->widget('CheckBrowserWidget', array(
		'sMessage'     => '<strong>Внимание!</strong> Для того чтобы пройти'
		. ' видеоидентификацию, Вам нужен браузер <strong>Chrome</strong> или <strong>Firefox</strong>'
		. ' последних версий.',
		'aHtmlOptions' => array(
			'style' => 'font-size: 15px;',
		)
	)); ?>

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

	<div class="row span12">
		<h4 class="center">
			Выберите способ идентификации личности </h4>

		<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
			'id'     => get_class($oClientCreateForm),
			'action' => Yii::app()->createUrl('/form/'),
		)); ?>
		<?php echo $form->hiddenField($oClientCreateForm, 'go_identification'); ?>
		<div class="row">
			<div class="span5">
				<h4 class="center">
					Видеоидентификация </h4>

				<p>
					Разрешите использование веб-камеры, если она есть, чтобы пройти видеоидентификацию </p>
			</div>
			<div class="span5 offset1">
				<h4 class="center">
					Идентификация в офисе Кредди </h4>

				<p>
					Город Москва, шоссе Энтузиастов 12, корп. 2 <br />ТЦ Город, главный вход, первый этаж </p>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="span5">
				<iframe src="<?php echo Yii::app()
					->createUrl('/form/checkwebcam'); ?>" width="400px" height="230px" frameborder="no" scrolling="no"></iframe>
			</div>
			<div class="span5 offset1">
				<a data-target="#fl-contacts" data-toggle="modal" href="#fl-contacts">
					<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/map-plan.png" alt="Карта" /> </a>

				<p>
					Отделение идентификации
					Кредди:<br /><a data-target="#fl-contacts" data-toggle="modal" href="#fl-contacts">узнать подробное
						местоположение</a>
				</p>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="span5">
				<?php echo $form->checkBoxRow($oClientCreateForm, 'agree', array(
					'id'       => 'agreeCheckBox',
					'onchange' => 'js: if($("#agreeCheckBox").prop("checked")){ $("#ident1").attr("disabled",false).removeClass("disabled");}else{$("#ident1").attr("disabled","disabled").addClass("disabled");}'
				));
				?>
			</div>
		</div>


		<div class="row">
			<div class="span5">
				<div class="form-actions ident-actions">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'id'          => 'ident1',
						'buttonType'  => 'button',
						'type'        => 'primary',
						'disabled'    => true,
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

				<p>Необходим компьютер с веб-камерой. Вы последовательно отправляете нам оригиналы документов через
					веб-камеру, и, не выходя из дома, получаете решение по займу.</p>
			</div>
			<div class="span5 offset1">
				<div class="form-actions ident-actions">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
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

				<p>Необходимо подъехать в отделение Кредди с паспортом и вторым документом для подтверждения
					личности.</p>
			</div>
		</div>
		<?php $this->endWidget(); ?>

		<?php $this->widget('YaMetrikaGoalsWidget', array(
			'iDoneSteps' => Yii::app()->clientForm->getCurrentStep(),
		)); ?>

	</div>
</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'privacy')); ?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4>Условия обслуживания и передачи информации</h4>
</div>

<div class="modal-body">
	<p>Заполняя и отправляя в адрес ООО «Финансовые Решения» (далее – Общество) данную форму анкеты и/или форму анкеты,
		заполненную мною дистанционным способом, я подтверждаю правильность указанных мною персональные данных,
		принадлежащих лично мне, а так же выражаю свое согласие на обработку (в том числе сбор, систематизацию,
		проверку, уточнение, изменение, обновление, использование, распространение (в том числе передачу третьим лицам),
		обезличивание, блокирование, уничтожение персональных данных) ООО «Финансовые Решения», место нахождения:
		Москва, Гончарная наб. д.1 стр.4, своих персональных данных, содержащихся в настоящей Анкете или переданных мною
		Обществу дистанционным способом. Персональные данные подлежат обработке (в том числе с использованием средств
		автоматизации) в целях принятия решения о предоставлении микрозайма, заключения, изменения, расторжения,
		дополнения, а также исполнения договоров микрозайма, дополнительных соглашений, заключенных или заключаемых
		впоследствии мною с ООО «Финансовые Решения». Настоящее согласие действует до момента достижения цели обработки
		персональных данных. Отзыв согласия на обработку персональных данных производится путем направления
		соответствующего письменного заявления Обществу по почте. Так же выражаю свое согласие на информирование меня
		Обществом о размерах микрозайма, полной сумме, подлежащей выплате, информации по продуктам или рекламной
		информации Общества по телефону, электронной почте, SMS – сообщениями.</p>

	<p>Направляя в ООО «Финансовые Решения» данную Анкету/или форму анкеты, заполненную мною дистанционным способом
		выражаю свое согласие на получение и передачу ООО «Финансовые Решения» (Общество) информации, предусмотренной
		Федеральным законом № 218 от 30.12.2004 "О кредитных историях", о своей кредитной истории в соответствующее бюро
		кредитных историй (Бюро кредитных историй определяет Общество по своему усмотрению). Список бюро указан на сайте
		Общества <a href="http://kreddy.ru/" target="_blank">www.kreddy.ru</a>, а также с тем, что в случае
		неисполнения, ненадлежащего исполнения и/или задержки исполнения мною своих обязательств по договорам
		микрозайма, заключенных с Обществом, Общество вправе раскрыть информацию об этом любым лицам (в т.ч.
		неопределенному кругу лиц) и любым способом (в т.ч. путем опубликования в средствах массовой информации).</p>

	<p>Направляя/подписывая в ООО «Финансовые Решения» данную форму Анкеты или анкету, заполненную мною дистанционным
		способом, подтверждаю, что ознакомлен с правилами предоставления микрозайма, со всеми условиями предоставления
		микрозайма. Также подтверждаю, что номер мобильного телефона, указанный в анкете, принадлежит лично мне.
		Ответственность за неправомерное использование номера мобильного телефона лежит на мне.</p>
</div>

<div class="modal-footer">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'       => 'Закрыть',
		'url'         => '#',
		'htmlOptions' => array('data-dismiss' => 'modal'),
	)); ?>
</div>

<?php $this->endWidget(); ?>
