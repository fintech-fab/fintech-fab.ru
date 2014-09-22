<?php
/**
 * @var LoanWidget     $this
 * @var SMSCodeForm    $oModel
 * @var IkTbActiveForm $form
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'loan-form',
	'action' => Yii::app()->createUrl('/account/doLoan'),
));

$this->widget('bootstrap.widgets.TbBox', array(
	'title'   => $this->getInfoTitle(),
	'content' => $this->getFullInfo(),
));
?>
	<div class="center">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'label'       => 'Получить условия',
			'type'        => 'primary',
			'buttonType'  => 'submit',
			'htmlOptions' => array(
				'name'  => 'loan_accept',
				'value' => '1'
			),
		));
		?>
	</div>
<?php
$this->endWidget();
