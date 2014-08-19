<?php
/**
 * File _rules.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Rule[] $rules
 */
?>

<?php foreach ($rules as $rule): ?>
	<?php echo $rule->signal->name; ?>
<?php endforeach; ?>

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