<?php
/**
 * File manage.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
?>

<ul class="tabs" data-tab>
	<li class="tab-title active"><a href="#panel0-1"><i class="fi-arrow-down"></i>&nbsp;События</a></li>
	<li class="tab-title"><a href="#panel0-2"><i class="fi-list-thumbnails"></i>&nbsp;Правила</a></li>
	<li class="tab-title"><a href="#panel0-3"><i class="fi-arrow-right"></i>&nbsp;Сигналы</a></li>
</ul>

<div class="tabs-content">
	<div class="content active" id="panel0-1">
		<?php foreach ($events as $event): ?>
			<?php echo $event->name; ?>
		<?php endforeach; ?>
		<p>First panel content goes here...</p>
	</div>
	<div class="content" id="panel0-2">
		<p>Second panel content goes here...</p>

		<script>
			$(document).ready(function () {
				$("#e1").select2();
			});
		</script>

		<select id="e1">
			<option value="AL">Alabama</option>
			<option value="WY">Wyoming</option>
			<option value="WY">Wyoming</option>
			<option value="WY">Wyoming</option>
		</select>
	</div>
	<div class="content" id="panel0-3">
		<p>Third panel content goes here...</p>
	</div>
</div>