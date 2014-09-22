<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

?>

<br /><br />
<div class="alert in alert-block alert-warning">
	<h4>Необходимо пройти идентификацию!</h4>
</div>
<div class="clearfix"></div>
<div class="center">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'    => 'submitButton',
		'url'   => $this->createUrl('identify'),
		'type'  => 'primary',
		'size'  => 'large',
		'label' => 'Пройти идентификацию',
	));
	?>
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'size'        => 'large',
		'label'       => 'Инструкция',
		'htmlOptions' => array(
			'class'   => 'btn-warning',
			'onClick' => 'return doOpenModalFrame(\'/pages/viewPartial/videoInstruction\', \'Инструкция\')',
		)
	));

	?>
</div><br />