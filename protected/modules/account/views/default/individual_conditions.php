<?php
/**
 * @var array $aConditions
 */

$this->pageTitle = Yii::app()->name . ' - Индивидуальные условия';

?>

<h4>Индивидуальные условия договора потребительского займа</h4>

<?php if (!empty($aConditions['active'])) {
	$aActive = $aConditions['active'];
	?>
	<div class="alert alert-warning">
		<h5>
			Индивидуальные условия № <?= $aActive['contract_number'] ?>
		</h5>

		<p>
			<?= CHtml::link('Посмотреть', array('/account/getDocument/', 'id' => $aActive['hash']), ['target' => '_blank']); ?>
			<?= CHtml::link('Скачать', array('/account/getDocument/', 'id' => $aActive['hash'], 'download' => '1')); ?>
		</p>
		<?php if (isset($aActive['dt_confirm_to'])) { ?>
			<h5>
				<b><strong>Подтвердить до:</strong> <?= SiteParams::formatRusDate($aActive['dt_confirm_to'], false); ?>
				</b>
			</h5>
			<br />
			<div class="center">
				<?php
				$this->widget('bootstrap.widgets.TbButton', array(
					'label' => 'Принять решение',
					'type'  => 'primary',
					'url'   => Yii::app()->createUrl('/account/doLoanConfirm'),
				));
				?>
			</div>
			<br />
		<?php } else { ?>
			<span class="alert-success">&nbsp;<strong>ПОДТВЕРЖДЕНЫ</strong>&nbsp;</span>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="alert alert-warning">
		<h5>Нет активных индивидуальных условий</h5>
	</div>
<?php } ?>
<br />

<?php
if (isset($aConditions['archive']) && count($aConditions['archive'])) {
	?>
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
	<?= CHtml::link('Общие условия', ['/static/docs/general_conditions.pdf'], ['target' => '_blank']); ?>
</div>