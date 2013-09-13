<?php
/**
 * @var $this StepsBreadCrumbsWidget
 */
?>
<ul <?= ($this->aHtmlOptions['class'] ? ' class="' . $this->aHtmlOptions['class'] . '"' : ''); ?>
	<?= ($this->aHtmlOptions['id'] ? ' id="' . $this->aHtmlOptions['id'] . '"' : ''); ?> >
	<?php

	$number = 1;

	foreach ($this->aCrumbs as $k => $crumb) {
		$sShownNumber = '<span class="steps_number">' . ((!empty($crumb[2])) ? $crumb[2] : $crumb[1]) . '.</span> ';
		if ($crumb[1] < $this->iCurStep) {
			echo '<li class="done">' . $sShownNumber . CHtml::link($crumb[0], array("form/$crumb[1]"));
		} elseif ($crumb[1] == $this->iCurStep) {
			echo '<li class="active">' . $sShownNumber . $crumb[0];
		} else {
			echo '<li>' . $sShownNumber . $crumb[0];
		}

		if (sizeof($this->aCrumbs) > ($number)) {
			echo '<span class="divider">' . $this->sDivider . '</span>';
		}

		echo '</li>';

		$number++;
	}
	?>
</ul>
