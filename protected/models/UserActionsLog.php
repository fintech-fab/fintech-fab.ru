<?php

/**
 * This is the model class for table "tbl_user_actions_log".
 *
 * The followings are the available columns in table 'tbl_user_actions_log':
 * @property integer $id
 * @property integer $type
 * @property string $ip
 * @property integer $count
 * @property string $dt_add
 */
class UserActionsLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserActionsLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_actions_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, ip, count, dt_add', 'required'),
			array('type, count', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, ip, count, dt_add', 'safe', 'on'=>'search'),
		);
	}

	public function scopeIpAndType($sIp,$iType)
	{
		$this->getDbCriteria()->addColumnCondition(array(
			'ip' => $sIp,
			'type' => $iType,
		));
		return $this;
	}

	public static function addAction($iType)
	{
		$sIp = Yii::app()->request->getUserHostAddress();

		$oUserAction = self::model()->scopeIpAndType($sIp,$iType)->find();
		if (!$oUserAction) {
			$oUserAction = new self;
		}

		$oUserAction->ip = $sIp;
		$oUserAction->type = $iType;
		$oUserAction->dt_add = date('Y-m-d H:i:s', time());
		$oUserAction->save();

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'ip' => 'Ip',
			'count' => 'Count',
			'dt_add' => 'Dt Add',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('dt_add',$this->dt_add,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}