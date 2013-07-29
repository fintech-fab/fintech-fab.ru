
<?php

/*Yii::app()->clientScript->registerScript('bottomtabs', '
	$(function() {
		$( "#tabs" ).tabs();
	});
', CClientScript::POS_HEAD);*/

?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
	'type'=>'tabs',
	'placement'=>'above', // 'above', 'right', 'below' or 'left'
	'tabs'=>$this->tabsArray,
	)); ?>


<?php //ниже реализация на основе jQuery UI ?>
<!--div id="tabs">

	<ul>
	<?php /*

		foreach($this->tabs as &$t)
		{
			echo '<li><a href="#tab-'.$t->tab_name.'">'.$t->tab_title.'</a></li>';
		}
		*/
	?>
	</ul>

	<?php /*
	foreach($this->tabs as &$t)
	{
		echo '<div id="tab-'.$t->tab_name.'">';
		echo '<p>'.$t->tab_content.'</p>';
		echo '</div>';
	}
	unset($t);*/
	?>


</div-->