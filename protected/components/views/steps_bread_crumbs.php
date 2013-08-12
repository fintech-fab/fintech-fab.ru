<?php
/**
 * @var $this StepsBreadCrumbsWidget
 */
?>
<ul <?php echo($this->aHtmlOptions['class'] ? ' class="' . $this->aHtmlOptions['class'] . '"' : ''); ?>
	<?php echo($this->aHtmlOptions['id'] ? ' id="' . $this->aHtmlOptions['id'] . '"' : ''); ?> >
	<?php

	$number = 1;

	foreach ($this->aCrumbs as $k => $crumb) {
		echo ((!empty($crumb[2])) ? $crumb[2] : $crumb[1]) . '. ';
		if ($crumb[1] < $this->iCurStep) {
			echo '<li class="done">' . CHtml::link($crumb[0], array("form/$crumb[1]")) . '</li>';
		} elseif ($crumb[1] == $this->iCurStep) {
			echo '<li class="active">' . CHtml::link($crumb[0], array("form/$crumb[1]")) . '</li>';
		} else {
			echo '<li>' . $crumb[0] . '</li>';
		}

		if (sizeof($this->aCrumbs) > ($number)) {
			echo '<span class="divider">' . $this->sDivider . '</span>';
		}

		$number++;
	}
	?>
</ul>
