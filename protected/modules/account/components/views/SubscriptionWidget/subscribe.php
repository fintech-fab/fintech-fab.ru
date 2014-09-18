<?php
/**
 * @var SubscriptionWidget  $this
 * @var IkTbActiveForm      $oForm
 * @var ClientSubscribeForm $oModel
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

	<div class="alert in alert-block alert-info">Обращаем твое внимание, что обработка запроса осуществляется ежедневно
		с 09:00 до 22:00 по московскому времени.
	</div>


	<div class="well" style="float:left;width: 42%;">
		<div style="height:225px;">
			<h4>ТАРИФ "МИНИМУМ"</h4>

			<p>Полная стоимость сервиса в месяц - от 1800 до 6800 р.</p>

			<p>Полная стоимость зависит от суммы перевода - от 2000 до 7000 р.</p>

			<p>Ограниченный функционал сервиса</p>

			<p>Перечисление денег только на мобильный</p>

		</div>

		<div class="center">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Выбрать',
				'type'  => 'primary',
				'id'    => 'minimum_products_but'
			));
			?>
		</div>
		<div class="clearfix"></div>
		<div class="center tariffs">
			<a href="<?= Yii::app()->createUrl('/site/tariffs') ?>" target="_blank">Подробнее о тарифах</a>
		</div>
	</div>


	<div class="well" style="float:right;width: 42%;">
		<div style="height:225px;">
			<h4>ТАРИФ "ВСЕ ВКЛЮЧЕНО"</h4>

			<p>Полная стоимость сервиса в месяц - от 990 до 1200 р.</p>

			<p>Стоимость не зависит от суммы перевода - от 2000 до 7000 р.</p>

			<p>Полный доступ ко всем возможностям функционал сервиса</p>

			<p>Перечисление денег на любую банковскую карту или на мобильный</p>
		</div>
		<div class="center">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Выбрать',
				'type'  => 'primary',
				'id'    => 'all_inclusive_products_but'
			));
			?>
		</div>
		<div class="clearfix"></div>
		<div class="center tariffs">
			<a href="<?= Yii::app()->createUrl('/site/tariffs') ?>" target="_blank">Подробнее о тарифах</a>
		</div>
	</div>
	<div class="clearfix"></div>

<?php
$oForm = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));
?>
	<div><?= $oForm->errorSummary($oModel); ?></div>


	<div id="minimum_products" style="display: none;">
		<?php
		$this->render($this->getWidgetViewsPath() . '/tariff_minimum');
		?>
	</div>
	<div id="all_inclusive_products" style="display: none;">
		<?php
		$this->render($this->getWidgetViewsPath() . '/tariff_all_inclusive', array(
			'oModel' => $oModel,
			'oForm'  => $oForm,
		));
		?>

	</div>
<?php
$this->endWidget();

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'registry_view')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Я ознакомлен со сведениями о внесении ООО «Финансовые Решения» в государственный реестр МФО</h4>
	</div>
	<div class="modal-body">
		<p>Направляя в МФО ООО «Финансовые Решения» данную форму заявления о предоставлении микрозайма, заполненного
			мною дистанционным способом, подтверждаю, что проинформирован о том, что МФО ООО «Финансовые Решения»
			включена в государственный реестр микрофинансовых организаций (регистрационный номер записи в
			государственном реестре микрофинансовых организаций 2110177000213 от 19 июля 2011 года). Я ознакомлен с тем,
			что копию свидетельства о внесении сведений об ООО «Финансовые Решения» в государственный реестр
			микрофинансовых организаций могу получить на официальном сайте Общества – www.kreddy.ru</p>
	</div>
	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Закрыть',
			'url'         => '#',
			'htmlOptions' => array('data-dismiss' => 'modal'),
		)); ?>
	</div>
<?php $this->endWidget();

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'not_public_view')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Я не являюсь иностранным должностным лицом, должностным лицом публичной международной организации, лицом,
			замещающим гос должность РФ</h4>
	</div>
	<div class="modal-body">
		<p>Направляя в МФО ООО «Финансовые Решения» данную форму заявления о предоставлении микрозайма, заполненного
			мною дистанционным способом, я подтверждаю, что не являюсь иностранным должностным лицом и/или должностным
			лицом публичной международной организации и/ или лицом, замещающим (занимающим) государственную должность
			Российской Федерации, должность членов Совета директоров Центрального банка Российской Федерации, должность
			федеральной государственной службы, назначение на которую и освобождение от которой осуществляется
			Президентом Российской Федерации или Правительством Российской Федерации, должность в Центральном банке
			Российской Федерации, государственной корпорации и иной организации, созданной Российской Федерацией на
			основании федеральных законов, включенной в перечни должностей, определяемой Президентом Российской
			Федерации, а также их супругом, близким родственником (родственником по прямой восходящей и нисходящей
			линии, полнородным и неполнородным (имеющим общих отца или мать), братом или сестрой, усыновителем и
			усыновленным)</p>
	</div>
	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Закрыть',
			'url'         => '#',
			'htmlOptions' => array('data-dismiss' => 'modal'),
		)); ?>
	</div>
<?php $this->endWidget();

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'my_interests_view')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Я действую в собственной выгоде и являюсь конечным бенефициарным владельцем</h4>
	</div>
	<div class="modal-body">
		<p>Направляя в МФО ООО «Финансовые Решения» данную форму заявления о предоставлении микрозайма, заполненного
			мною дистанционным способом, я подтверждаю, что действую к собственной выгоде, иное лицо, к выгоде которого
			я действую (выгодоприобретатель) отсутствует, а так же подтверждаю, что лицо, контролирующее мои действия
			(бенефициарный владелец), отсутствует</p>
	</div>
	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Закрыть',
			'url'         => '#',
			'htmlOptions' => array('data-dismiss' => 'modal'),
		)); ?>
	</div>
<?php $this->endWidget();

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'auto_debiting_view')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Я согласен на списание задолженности с моей банковской карты при нарушении условий Договора</h4>
	</div>
	<div class="modal-body">
		<p>Направляя в МФО ООО «Финансовые Решения» данную форму заявления о предоставлении микрозайма, заполненного
			мною дистанционным способом, я даю согласие на списание денежных средств с моего лицевого счета (номера
			мобильного телефона), указанного в заявлении на предоставление микрозайма, у оператора услуг сотовой связи в
			счет погашения задолженности по Договору, а так же на списание денежных средств с моей Банковской карты,
			указанной в Личном кабинете на сайте Компании, в счет погашения задолженности по Договору</p>
	</div>
	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Закрыть',
			'url'         => '#',
			'htmlOptions' => array('data-dismiss' => 'modal'),
		)); ?>
	</div>
<?php $this->endWidget();


Yii::app()->clientScript->registerScript('tariff_changer',
	'
		$("#minimum_products_but").click(function(){
			$("#minimum_products").show();
			$("#all_inclusive_products").hide();
		});

		$("#all_inclusive_products_but").click(function(){
			$("#minimum_products").hide();
			$("#all_inclusive_products").show();
		});
	'
);