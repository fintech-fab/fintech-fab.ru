<?php
/**
 * Class DbCleanerCommand
 */
class DbCleanerCommand extends CConsoleCommand
{
	public function actionClearDb()
	{
		$sSql = "
		UPDATE `tbl_client`
SET `password` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `password` != '';

UPDATE `tbl_client`
SET `job_phone` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `job_phone` IS NOT NULL;

UPDATE `tbl_client`
SET `first_name` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `first_name` != '';

UPDATE `tbl_client`
SET `last_name` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `last_name` != '';

UPDATE `tbl_client`
SET `third_name` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `third_name` != '';

UPDATE `tbl_client`
SET `passport_series` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `passport_series` != '';

UPDATE `tbl_client`
SET `passport_number` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `passport_number` != '';

UPDATE `tbl_client`
SET `passport_issued` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `passport_issued` != '';

UPDATE `tbl_client`
SET `passport_code` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `passport_code` != '';

UPDATE `tbl_client`
SET `passport_date` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `passport_date` != '';

UPDATE `tbl_client`
SET `document_number` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `document_number` != '';

UPDATE `tbl_client`
SET `address_reg_address` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `address_reg_address` != '';

UPDATE `tbl_client`
SET `address_res_address` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `address_res_address` != '';

UPDATE `tbl_client`
SET `relatives_one_fio` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `relatives_one_fio` != '';

UPDATE `tbl_client`
SET `relatives_one_phone` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `relatives_one_phone` != '';

UPDATE `tbl_client`
SET `friends_fio`='*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `friends_fio` != '';

UPDATE `tbl_client`
SET `friends_phone`='*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `friends_phone` != '';

UPDATE `tbl_client`
SET `secret_question` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `secret_question` != '';

UPDATE `tbl_client`
SET `secret_answer` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `secret_answer` != '';

UPDATE `tbl_client`
SET `numeric_code` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `numeric_code` != '';

UPDATE `tbl_client`
SET `sms_code` = '*'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY)) AND `sms_code` != '';

UPDATE `tbl_client`
SET `flag_cleared` = '1'
WHERE `dt_update` <= TIMESTAMP((NOW() - INTERVAL 1 DAY));
		";

		Yii::app()->db->createCommand($sSql)->execute();
	}
}