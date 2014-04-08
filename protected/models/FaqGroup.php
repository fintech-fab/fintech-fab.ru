<?php

/**
 * This is the model class for table "tbl_faq_groups".
 *
 * The followings are the available columns in table 'tbl_faq_groups':
 *
 * @property integer       $id
 * @property string        $title
 * @property integer       $sort_order
 *
 * The followings are the available model relations:
 * @property FaqQuestion[] $questions
 */
class FaqGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_faq_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('sort_order', 'numerical', 'integerOnly' => true),
			array('title', 'length', 'max' => 100),
			array('title', 'match', 'pattern' => '/^[а-яёa-z0-9?,.!\-—:\s]+$/ui', 'message' => 'Заголовок может содержать только буквы, цифры, знаки препинания и пробелы'),
			array('show_site1, show_site2', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			array('id, title, sort_order, show_site1, show_site2', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'questions' => array(self::HAS_MANY, 'FaqQuestion', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'         => 'ID',
			'title'      => 'Заголовок',
			'sort_order' => 'Порядок сортировки',
			'show_site1' => 'Показывать на kreddy.ru',
			'show_site2' => 'Показывать на ivanovo.kreddy.ru'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('sort_order', $this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
				'pageSize' => 50,
			),
			'sort'       => array(
				'defaultOrder' => 'sort_order ASC',
			)
		));
	}

	/**
	 * @return array
	 */
	public function defaultScope()
	{
		return array(
			'alias' => 'faq_group',
			'order' => 'faq_group.sort_order ASC, faq_group.id ASC',
		);
	}

	/**
	 * @param $iSite
	 *
	 * @return FaqGroup
	 */
	private function scopeSiteGroups($iSite)
	{
		$oCriteria = new CDbCriteria();
		if ($iSite === 1) {
			$oCriteria->addColumnCondition(array(
				'faq_group.show_site1' => '1',
			));
		} elseif ($iSite === 2) {
			$oCriteria->addColumnCondition(array(
				'faq_group.show_site2' => '1',
			));
		}

		$oCriteria->order = 'faq_group.sort_order ASC, faq_group.id ASC';

		$this->setDbCriteria($oCriteria);

		return $this;
	}

	/**
	 * @param $iSite
	 *
	 * @return CActiveRecord[]
	 */
	public static function getSiteGroups($iSite)
	{
		$aScopes = array();

		if ($iSite === 1) {
			$aScopes[] = 'site1';
		} elseif ($iSite === 2) {
			$aScopes[] = 'site2';
		}

		$aResult = self::model()->scopeSiteGroups($iSite)->with(array(
			'questions' => array(
				'scopes' => $aScopes
			),
		))->findAll();

		return $aResult;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return FaqGroup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
