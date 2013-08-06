<?php
/* @var FormController $this*/
/* @var InviteToIdentification $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Ваша заявка отправлена!
 * Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
 * Предлагаем дополнительно пройти видеорегистрацию
 */


$this->pageTitle=Yii::app()->name;

?>
<div class="row">
	<div class="row span12">
		<h3>
			Для получения займа необходимо подтвердить свою личность.<br/>
			Как вы хотите пройти процедуру идентификации?
		</h3>
	</div>
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id' => get_class($oClientCreateForm),
		'action' => Yii::app()->createUrl('/form/'),
	)); ?>
	<div class="row">
		<div class="span5">
			<?php echo $form->hiddenField($oClientCreateForm, 'go_identification'); ?>
			<div class="clearfix"></div>
			<div class="form-actions">
				<? $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit',
					'type'       => 'primary',
					'label'      => 'Пройти видеоидентификацию на сайте →',
					'htmlOptions' => array('onclick' => 'js:  $("#'.get_class($oClientCreateForm).'_go_identification").val("1");')
				)); ?>
			</div>
			<br/>
			<p>Необходим компьютер с веб-камерой. Вы последовательно предъявляете оригиналы документов камере и не выходя из дома получаете решение по займу.</p>
		</div>
		<div class="span5 offset2">
			<div class="clearfix"></div>
			<div class="form-actions">
				<? $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit',
					'type'       => 'primary',
					'label'      => 'Пройти видеоидентификацию в отделении →',
					'htmlOptions' => array('onclick' => 'js: $("#'.get_class($oClientCreateForm).'_go_identification").val("2");')
				)); ?>
			</div>
			<br/>
			<p>Необходимо подъехать на стойку идентификации сервиса Кредди с паспортом и вторым документом.</p>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<?


?>
