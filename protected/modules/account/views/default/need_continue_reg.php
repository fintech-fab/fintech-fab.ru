<?php
/* @var DefaultController $this */
/* @var VideoIdentifyForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name.' - Продожение регистрации'

?>

<br /><br />
<div class="alert in alert-block alert-warning span7">
	<h4>Тебе необходимо заполнить анкету</h4>
</div>
<div class="clearfix"></div>
<div class="center">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'id'    => 'submitButton',

		'type'  => 'primary',
		'size'  => 'large',
		'label' => 'Заполнить анкету',
		'url'   => Yii::app()->createUrl('/account/continueForm'),
	));
	?>
</div>

<br />