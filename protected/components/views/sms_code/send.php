<?php
/**
 * @var IkTbActiveForm $form
 * @var SMSCodeForm    $oModel
 * @var string         $sAction
 */
?>
<div class="clearfix"></div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => get_class($oModel),
	'type'        => 'horizontal',
	'htmlOptions' => array(
		'class' => 'form-actions',
		'autocomplete' => 'off',
	),
	'action'      => Yii::app()->createUrl($sAction),
));

echo $form->hiddenField($oModel, 'sendSmsCode');
?>
<div class="center">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'       => 'primary',
		'size'       => 'small',
		'buttonType' => 'submit',
		'label'      => 'Отправить SMS c кодом на номер +7'.Yii::app()->user->getMaskedId(),
	));
	?>
</div>
<?php

$this->endWidget();

?>
<div class="clearfix"></div>