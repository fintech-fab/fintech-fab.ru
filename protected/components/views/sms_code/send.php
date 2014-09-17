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
		'class'        => "span10",
		'autocomplete' => 'off',
	),
	'action'      => Yii::app()->createUrl($sAction),
));

echo $form->hiddenField($oModel, 'sendSmsCode');
?>
<div class="row">
	<div class="span5">
		<div class="form-actions">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'type'       => 'primary',
				'size'       => 'small',
				'buttonType' => 'submit',
				'label'      => 'Отправить SMS с кодом',
			));
			?>
		</div>
	</div>
</div>
<?php

$this->endWidget();

?>
<div class="clearfix"></div>