<?php
/**
 * File manage.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var string $_events
 * @var string $_rules
 * @var string $_signals
 */
?>

<ul class="tabs" data-tab>
	<li class="tab-title active">
		<a href="#panel0-1">
			<i class="fi-arrow-down"></i>&nbsp;События&nbsp;&nbsp;и&nbsp;&nbsp;<i class="fi-list-thumbnails"></i>&nbsp;Правила
		</a>
	</li>
	<li class="tab-title"><a href="#panel0-2"><i class="fi-arrow-right"></i>&nbsp;Сигналы</a></li>
</ul>

<div class="tabs-content">
	<div class="content active" id="panel0-1">
		<?php echo $_events; ?>
		<?php echo $_rules; ?>
	</div>
	<div class="content" id="panel0-2">
		<?php echo $_signals; ?>
	</div>
</div>