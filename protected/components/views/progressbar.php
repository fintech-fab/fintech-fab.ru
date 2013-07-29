<?php
	/*$this->widget('zii.widgets.jui.CJuiProgressBar',array(
		'id'=>'progressBar',
		'value'=>0,
		'options'=>$this->options,
		'htmlOptions'=>$this->htmlOptions,
	));*/

	$this->widget('bootstrap.widgets.TbProgress',array(
		'type'=>'info',
		//'id'=>'progressBar',
		'percent'=>0,
		//'options'=>$this->options,
		'htmlOptions'=>$this->htmlOptions,
	));

	//функция обновления прогресс-бара
	Yii::app()->clientScript->registerScript('yiiactiveform', "
		function progressBarUpdate(form, data, hasError)
		{
			var newValue = 100/".$this->allFields." * ( $('.success').size()+".$this->filledFields."); //считаем число полей с классом success (успешная валидация) и рассчитываем прогресс
			$('.bar').width(newValue + '%');//устанавливаем прогресс
			return false;
		}
	", CClientScript::POS_HEAD);

?>
<?php
$script = "		$(window).load(function () {\n			";
$attr=$this->model->attributeNames();
foreach($attr as &$a)
{
	$script.='$("#'.get_class($this->model).'_'.$a.'").blur();'."\n			";
}
unset($a);
$script.="\n		});";

Yii::app()->clientScript->registerScript('progressbar', $script, CClientScript::POS_HEAD);
?>
