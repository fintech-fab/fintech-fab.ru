<?php
/**
 * @var array $aConditions
 */
?>

<h4>Индивидуальные условия договора потребительского займа</h4>

<?php if (!empty($aConditions['active'])) {
	$aActive = $aConditions['active'];
	?>
	<div class="alert alert-warning">
		<h5>
			Индивидуальные условия № <?= $aActive['contract_number'] ?>
		</h5>
		<?php if (isset($aActive['dt_confirm_to'])) { ?>
			<h5>
				<b>Подтвердить до: <?= SiteParams::formatRusDate($aActive['dt_confirm_to'], false); ?></b>
			</h5>
			<br />
			<div class="center">
				<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'size'        => 'large',
					'label'       => 'Принять',
					'htmlOptions' => array(
						'class' => 'btn-success',
					),
				));
				?>  <?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'size'        => 'large',
					'label'       => 'Отклонить',
					'htmlOptions' => array(
						'class' => 'btn-warning',
					)
				));
				?>
			</div>
			<br />
		<?php } else { ?>
			<span class="alert-success"> ПОДТВЕРЖДЕНЫ </span>
		<?php } ?>
	</div>
<?php } ?>
<br />

<?php if (count($aConditions)) { ?>
	<div><a href="#" class="dotted" onclick="$('#archive_conditions').toggle(); return false;">Архив</a></div><br />

	<table id="archive_conditions" style="display: none;">
		<?php foreach ($aConditions['archive'] as $aCondition) { ?>
			<tr>
				<td><?= CHtml::link('Посмотреть', array('/account/getDocument/', 'id' => $aCondition['hash'])); ?></td>
				<td><?= CHtml::link('Скачать', array('/account/getDocument/', 'id' => $aCondition['hash'], 'download' => '1')); ?></td>
				<td>№ <?= $aCondition['contract_number'] ?></td>
				<td><?= SiteParams::formatRusDate($aCondition['dt_add'], false); ?></td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

<h4 style="margin-top: 20px;">Общие условия</h4>
<div>
	<?= CHtml::link('Общие условия', array('/static/docs/general_conditions.pdf')); ?>
</div>