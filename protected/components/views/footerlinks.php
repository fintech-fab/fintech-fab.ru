<?php


?>

<div id="footerlinks">

	<p>
		<?php

		foreach($this->links as &$l)
		{
			//echo '<a rel="dialog:open" href="#'.$l->link_name.'">'.$l->link_title.'</a> &middot;';
		   echo CHtml::link($l->link_title, '#fl-'.$l->link_name, array('onclick' => '$("#fl-'.$l->link_name.'").dialog("open"); return false;',));
		}
		unset($l);

		?>
	</p>

	<?php

	foreach($this->links as &$l)
	{
		$this->beginWidget('zii.widgets.jui.CJuiDialog',
			array(
				'id'=>'fl-'.$l->link_name,
				'options'=>array(
					'title'=>$l->link_title,
					'autoOpen' => false,
					'modal'=>true,
					'resizable'=> false,
					'width'=>760,
					'height'=>500,
					'buttons'=>array('Закрыть'=>'js:function(){$(this).dialog("close")}')
				),
				'themeUrl'=>Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets').'/').'/jui/css',
				'theme'=>'base',
				'cssFile'=>'jquery-ui.css',
			)
		);

		echo ''.$l->link_content.'';

		$this->endWidget('zii.widgets.jui.CJuiDialog');
	}
	unset($l);
	?>


</div>