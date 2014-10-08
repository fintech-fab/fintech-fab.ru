<?php

/**
 * This is the model class for table "tbl_leads_history".
 *
 * The followings are the available columns in table 'tbl_leads_history':
 *
 * @property string  $id
 * @property string  $lead_name
 * @property string  $parent_id
 * @property string  $first_id
 * @property integer $flag_showed
 * @property integer $flag_reimplemented
 * @property string  $dt_add
 * @property string  $dt_show
 * @property string  $webmaster_id
 *
 * @method LeadsHistory findByPk($pk, $condition = '', $params = array())
 */
class LeadsHistory extends CActiveRecord
{
	/**
	 * @param $sUid
	 * @param $mSubId
	 * @param $iFirstOrderId
	 * @param $iParentOrderId
	 *
	 * @return LeadsHistory
	 */
	public static function generate($sUid, $mSubId, $iFirstOrderId, $iParentOrderId)
	{
		$oLead = new self();
		$oLead->lead_name = $sUid;
		$oLead->webmaster_id = $mSubId;
		$oLead->first_id = $iFirstOrderId;
		$oLead->parent_id = $iParentOrderId;
		$oLead->save();

		// Генерация случайного идентификатора
		$iAttempts = 10;
		while (true) {
			try {
				$oLead->id = floor(SiteParams::getTime() / 3600) * 3600 + mt_rand(0, 3600);
				$oLead->save();
				break;
			} catch (Exception $e) {
				// Не удоволетверено условие по уникальности
				$iAttempts--;

				if ($iAttempts <= 0) {
					unset($oLead->id);

					$oLead->save();
					break;
				}
			}
		}

		return $oLead;
	}

	/**
	 * @return string
	 */
	public function tableName()
	{
		return 'tbl_leads_history';
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('lead_name', 'required'),
			array('flag_showed', 'numerical', 'integerOnly' => true),
			array('lead_name, webmaster_id', 'length', 'max' => 255),
			array('parent_id, first_id', 'length', 'max' => 10),
			array('id, lead_name, webmaster_id, parent_id, first_id, flag_showed, dt_add, dt_show', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'lead_name'   => 'Lead Name',
			'webmaster_id'=> 'Webmaster',
			'parent_id'   => 'Parent',
			'first_id'    => 'First',
			'flag_showed' => 'Flag Showed',
			'dt_add'      => 'Dt Add',
			'dt_show'     => 'Dt Show',
		);
	}

	/**
	 * @return CActiveDataProvider
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('lead_name', $this->lead_name, true);
		$criteria->compare('webmaster_id', $this->webmaster_id, true);
		$criteria->compare('parent_id', $this->parent_id, true);
		$criteria->compare('first_id', $this->first_id, true);
		$criteria->compare('flag_showed', $this->flag_showed);
		$criteria->compare('dt_add', $this->dt_add, true);
		$criteria->compare('dt_show', $this->dt_show, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class'           => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'dt_add',
				'updateAttribute' => null,
			)
		);
	}

	/**
	 * @param string $className
	 *
	 * @return LeadsHistory
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}