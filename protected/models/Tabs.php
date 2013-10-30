<?php

/**
 * This is the model class for table "tbl_bottom_tabs".
 *
 * The followings are the available columns in table 'tbl_bottom_tabs':
 *
 * @property integer $tab_id
 * @property string  $tab_name
 * @property string  $tab_title
 * @property string  $tab_content
 * @property integer $tab_order
 * @property integer $show_site1
 * @property integer $show_site2
 * @method Tabs cache()
 */
class Tabs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
	 * @return Tabs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CActiveRecord[]
	 */

	public static function getAllTabs()
	{
		//TODO сделать кэш через зависимости
		return self::model()->cache(60)->findAll(array('order' => 'tab_order'));
	}

	/**
	 * @param $iSite
	 *
	 * @return CActiveRecord[]
	 */
	public static function getSiteTabs($iSite)
	{
		$aResult = self::model()->cache(60)->scopeSiteTabs($iSite)->findAll();

		return $aResult;
	}

	/**
	 * @param $iSite
	 *
	 * @return $this
	 */
	private function scopeSiteTabs($iSite)
	{
		$oCriteria = new CDbCriteria();
		if ($iSite === 1) {
			$oCriteria->addColumnCondition(array(
				'show_site1' => '1',
			));
		} elseif($iSite === 2) {
			$oCriteria->addColumnCondition(array(
				'show_site2' => '1',
			));
		}

		$oCriteria->order = 'tab_order ASC';

		$this->setDbCriteria($oCriteria);

		return $this;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_bottom_tabs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tab_name, tab_title, tab_content', 'required'),
			array('tab_order', 'numerical', 'integerOnly' => true),
			array('tab_name', 'length', 'max' => 20),
			array('tab_name', 'match', 'pattern' => '/^[a-z][a-z0-9]+$/ui', 'message' => 'Имя может содержать только цифры и латинские символы, первым символом должна быть буква'),
			array('tab_name', 'unique', 'message' => 'Вкладка должна иметь уникальное имя'),
			array('tab_title', 'length', 'max' => 30),
			array('tab_title', 'match', 'pattern' => '/^[а-яёa-z0-9?,.!\-—: ]+$/ui', 'message' => 'Заголовок может содержать только буквы, цифры, знаки препинания и пробелы'),
			array('show_site1, show_site2', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('tab_id, tab_name, tab_title, tab_content, tab_order, show_site1, show_site2', 'safe', 'on' => 'search'),

		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tab_id'      => 'ID вкладки',
			'tab_name'    => 'Имя вкладки',
			'tab_title'   => 'Заголовок вкладки',
			'tab_content' => 'Содержимое вкладки',
			'tab_order'   => 'Номер вкладки в последовательности',
			'show_site1'  => 'Показывать на kreddy.ru',
			'show_site2'  => 'Показывать на ivanovo.kreddy.ru'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('tab_id', $this->tab_id);
		$criteria->compare('tab_name', $this->tab_name, true);
		$criteria->compare('tab_title', $this->tab_title, true);
		$criteria->compare('tab_content', $this->tab_content, true);
		$criteria->compare('tab_order', $this->tab_order);
		$criteria->compare('show_site1', $this->show_site1);
		$criteria->compare('show_site2', $this->show_site2);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort'     => array(
				'defaultOrder' => 'tab_order ASC',
			)
		));
	}

	/**
	 * @return bool
	 */

	protected function beforeValidate()
	{
		if (parent::beforeValidate()) {
			$this->tab_title = trim($this->tab_title);
		}

		return true;
	}

	protected function afterValidate()
	{
		$p = new CHtmlPurifier;
		$p->options = array(
			'HTML.AllowedElements'   => array("div", "p", "ul", "ol", "li", "h3", "h4", "h5", "h6", "img", "a", "b", "i", "s", "span", "u", "em", "strong", "del", "blockquote", "sup", "sub", "pre", "br", "hr", "table", "tbody", "thead", "tr", "td", "th"),
			'HTML.AllowedAttributes' => array("img.src", "img.alt", "img.title", "img.width", "img.height", "a.href", "a.title", "*.style", "*.class"),
		);
		$this->tab_content = $p->purify($this->tab_content);
		parent::afterValidate();
	}
}
