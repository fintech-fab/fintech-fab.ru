<?php
	$this->widget('zii.widgets.jui.CJuiProgressBar',array(
		'id'=>'progressBar',
		'value'=>0,
		'options'=>$options,
		'htmlOptions'=>$htmlOptions,
	));

	//функция обновления прогресс-бара
	Yii::app()->clientScript->registerScript('yiiactiveform', "
		function progressBarUpdate(form, data, hasError)
		{
			var newValue = 100/16 * ( $('.success').size()+".$startFilledFields."); //считаем число полей с классом success (успешная валидация) и рассчитываем прогресс

			jQuery('#progressBar').progressbar({'value': newValue});//устанавливаем прогресс
			return false;
		}
	", CClientScript::POS_HEAD);

?>

<script type="text/javascript">
	onload = function()
	{
		<?php
			//генерируем список полей на валидацию по onload()
			//валидация происходит через ajax по событию jQuery blur()
			$attr=$model->attributeNames();
			foreach($attr as &$a)
			{
				echo '$("#'.get_class($model).'_'.$a.'").blur();'."\n		";
			}
			unset($a);
			echo "\n";
		?>
	}
</script>