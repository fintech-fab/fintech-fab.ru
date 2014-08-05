<?php
/**
 * @var array $aConditions
 */
?>

<h4>Индивидуальные условия договора потребительского займа</h4>

<?php if (!empty($aConditions['active'])) {
	$aActive = $aConditions['active'];
	?>
	<div>
		Индивидуальные условия <b>Подтвердить до: <?= date('d.m.Y', strtotime($aActive['dt_confirm_to'])); ?></b>
	</div>

<?php } ?>

<br /><br />
<div><a href="#" class="dotted" onclick="$('#archive_conditions').toggle(); return false;">Архив</a></div><br />

<table id="archive_conditions" style="display: none;">
	<?php foreach ($aConditions['archive'] as $aCondition) { ?>
		<tr>
			<td><?= CHtml::link('Посмотреть', array('/account/getDocument/', 'id' => $aCondition['hash'])); ?></td>
			<td><?= CHtml::link('Скачать', array('/account/getDocument/', 'id' => $aCondition['hash'], 'download' => '1')); ?></td>
			<td><?= $aCondition['status'] ?></td>
			<td><?= $aCondition['dt_add'] ?></td>
		</tr>
	<?php } ?>
</table>

<h4 style="margin-top: 20px;">Общие условия</h4>
<div>
	<?= CHtml::link('Общие условия', array('/static/docs/general_conditions.pdf')); ?>
</div>