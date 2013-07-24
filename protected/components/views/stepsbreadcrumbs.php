<ul<?php echo ($this->htmlOptions['class'] ? ' class="' . $this->htmlOptions['class'] . '"' : ''); ?><?php echo ($this->htmlOptions['id'] ? ' id="' . $this->htmlOptions['id'] . '"' : ''); ?>>
	<?php

	$n = 1;

	foreach($this->crumbs as $k => $crumb)
	{
		echo $n.'. ';
		if($n <= $this->curStep) {
			echo '<li class="done">'.CHtml::link($crumb[0],$crumb[1]).'</li>';
		}
		else
		{
			echo '<li><a href="">'.$crumb[0].'</a></li>';
		}

		if(sizeof($this->crumbs) > ($n))
		{
			echo '<span class="divider">' . $this->divider . '</span>';
		}

		echo '</li>';

		$n++;
	}

	?>
</ul>
