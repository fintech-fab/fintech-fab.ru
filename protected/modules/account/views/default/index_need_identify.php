<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

?>

<br /><br />
<div class="alert in alert-block alert-warning">
	<h4>Вам необходимо пройти идентификацию!</h4>
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
</div><br />