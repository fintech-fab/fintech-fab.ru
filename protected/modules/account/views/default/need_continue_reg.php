<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

?>

<br /><br />
<div class="alert in alert-block alert-warning span7">
	<h4>Вам необходимо заполнить анкету</h4>
</div>
<div class="clearfix"></div>
<div class="center">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'         => 'submitButton',
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'large',
		'label'      => 'Заполнить анкету',
	));
	?>
</div>

<br />