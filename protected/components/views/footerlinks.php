<?php


?>

<div id="footerlinks">

	<p>
		<?php

		foreach($this->links as &$l)
		{
			if($l->link_url=='')
			{
				echo CHtml::link($l->link_title, '#fl-'.$l->link_name, array('data-target' => '#fl-'.$l->link_name,'data-toggle'=>'modal'));
			}
			else if(strpos($l->link_url, Yii::app()->request->getBaseUrl(true)) === false)
			{
				echo CHtml::link($l->link_title, $l->link_url, array('target' => '_blank'));
			}
			else
			{
				echo CHtml::link($l->link_title, $l->link_url);
			}

			if($l != end($this->links))
			{
				echo ' &middot; ';
			}
		}
		unset($l);
		?>
	</p>




	<?php



	foreach($this->links as &$l)
	{
		if($l->link_url=='')
		{
			$this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'fl-'.$l->link_name));
			echo '	<div class="modal-header">';
			echo '		<a class="close" data-dismiss="modal">&times;</a>';
			echo '		<h2	>'.$l->link_title.'</h2>';
			echo '	</div>';
			echo '	<div class="modal-body">';
			echo '		'.$l->link_content;
			echo '	</div>';

			echo '	<div class="modal-footer">';

			$this->widget('bootstrap.widgets.TbButton', array(
				'label'=>'Закрыть',
				'url'=>'#',
				'htmlOptions'=>array('data-dismiss'=>'modal'),
			));

			echo '</div>';
			$this->endWidget();
		}
	}
	unset($l);
	?>


</div>
