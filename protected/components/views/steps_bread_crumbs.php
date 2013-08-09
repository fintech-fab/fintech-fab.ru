<ul <?php echo($this->htmlOptions['class'] ? ' class="' . $this->htmlOptions['class'] . '"' : ''); ?><?php echo($this->htmlOptions['id'] ? ' id="' . $this->htmlOptions['id'] . '"' : ''); ?> >
	<?php

	$number = 1;

	foreach ($this->crumbs as $k => $crumb) {
		echo ((!empty($crumb[2])) ? $crumb[2] : $crumb[1]) . '. ';
		if ($crumb[1] < $this->curStep) {
			echo '<li class="done">' . CHtml::link($crumb[0], array("form/$crumb[1]")) . '</li>';
		} elseif ($crumb[1] == $this->curStep) {
			echo '<li class="active">' . CHtml::link($crumb[0], array("form/$crumb[1]")) . '</li>';
		} else {
			echo '<li><a href="">' . $crumb[0] . '</a></li>';
		}

		if (sizeof($this->crumbs) > ($number)) {
			echo '<span class="divider">' . $this->divider . '</span>';
		}

		$number++;
	}
	?>
</ul>
