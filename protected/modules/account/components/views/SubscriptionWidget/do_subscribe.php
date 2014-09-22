<?php
/**
 * @var SubscriptionWidget $this
 * @var SMSCodeForm        $oModel
 * @var IkTbActiveForm     $form
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
?>
	<div class="center">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Отправить запрос на подключение',
			'type'        => 'primary',
			'buttonType'  => 'submit',
			'htmlOptions' => array(
				'name'  => 'subscribe_accept',
				'value' => '1'
			),
		));
		?>
	</div>
<?php
$this->endWidget();
