<?php
/**
 * @var array $aConditions
 */
?>
<table>
	<?php foreach ($aConditions as $aCondition) { ?>
		<tr>
			<td><?= CHtml::link('Посмотреть', array('/account/getDocument/', 'id' => $aCondition['hash'])); ?></td>
			<td><?= CHtml::link('Скачать', array('/account/getDocument/', 'id' => $aCondition['hash'], 'download' => '1')); ?></td>
			<td><?= $aCondition['status'] ?></td>
			<td><?= $aCondition['dt_add'] ?></td>
		</tr>
	<?php } ?>
</table>