<?php
/**
 * @var array $aPassportData
 */

?>
<?php if(Yii::app()->adminKreddyApi->getPassportDataField('old_passport_series')): ?>
<strong>Старый паспорт:</strong>
<ul>
	<li>
		<strong>Серия и номер:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('old_passport_series'); ?>
		&nbsp;/&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('old_passport_number'); ?>
	</li>

</ul>
<?php endif; ?>

<strong>Паспорт:</strong>
<ul>
	<li>
		<strong>Фамилия:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('last_name') ?>
	</li>
	<li><strong>Имя:</strong>&nbsp; <?= Yii::app()->adminKreddyApi->getPassportDataField('first_name') ?>
	</li>
	<li><strong>Отчество:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('third_name') ?></li>
	<li>
		<strong>Серия и номер:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('passport_series'); ?>
		&nbsp;/&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('passport_number'); ?>
	</li>
	<li><strong>Дата выдачи:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('passport_date') ?>
	</li>
	<li>
		<strong>Код подразделения:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('passport_code') ?>
	</li>
	<li>
		<strong>Выдан:</strong>&nbsp;<?= Yii::app()->adminKreddyApi->getPassportDataField('passport_issued') ?>
	</li>
</ul>
