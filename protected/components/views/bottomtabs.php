
<?php

Yii::app()->clientScript->registerScript('bottomtabs', '
	$(function() {
		$( "#tabs" ).tabs();
	});
', CClientScript::POS_HEAD);


?>

<div id="tabs">

	<ul>
	<?php

		foreach($this->tabs as &$t)
		{
			echo '<li><a href="#'.$t->tab_name.'">'.$t->tab_title.'</a></li>';
		}

	?>
	</ul>

	<?php
	foreach($this->tabs as &$t)
	{
		echo '<div id="'.$t->tab_name.'">';
		echo '<p>'.$t->tab_content.'</p>';
		echo '</div>';
	}
	unset($t);
	?>


</div>