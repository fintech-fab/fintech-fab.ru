<?php

/**
 * This is the model class for table "tbl_faq_questions".
 *
 * The followings are the available columns in table 'tbl_faq_questions':
 *
 * @property integer  $id
 * @property string   $title
 * @property string   $answer
 * @property integer  $group_id
 * @property integer  $sort_order
 *
 * The followings are the available model relations:
 * @property FaqGroup $group
 */
class FaqQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_faq_questions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, answer, group_id', 'required'),
			array('group_id, sort_order', 'numerical', 'integerOnly' => true),
			array('title', 'length', 'max' => 500),
			// The following rule is used by search().
			array('id, title, answer, group_id, question_order', 'safe', 'on' => 'search'),
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
			'group' => array(self::BELONGS_TO, 'FaqGroup', 'group_id'),
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
			'answer'     => 'Ответ',
			'group_id'   => 'Категория',
			'sort_order' => 'Порядок сортировки',
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
		$criteria->compare('answer', $this->answer, true);
		$criteria->compare('group_id', $this->group_id);
		$criteria->compare('sort_order', $this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort'     => array(
				'defaultOrder' => 'sort_order ASC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return FaqQuestion the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array
	 */
	public function defaultScope()
	{
		return array(
			'alias' => 'faq_question',
			'order' => 'faq_question.sort_order ASC',
		);
	}

	protected function afterValidate()
	{
		$p = new CHtmlPurifier;
		$p->options = array(
			'Filter.YouTube'           => true,
			'HTML.SafeObject'          => true,
			'HTML.SafeIframe'          => true,
			'Output.FlashCompat'       => true,
			'URI.SafeIframeRegexp'     => '%^(http://|//)(www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/)%',
			'Attr.AllowedFrameTargets' => array('_blank', '_self', '_parent', '_top'),
			'HTML.AllowedElements'     => array("div", "p", "ul", "ol", "li", "h3", "h4", "h5", "h6", "img", "a", "b", "i", "s", "span", "u", "em", "strong", "del", "blockquote", "sup", "sub", "pre", "br", "hr", "table", "tbody", "thead", "tr", "td", "th", "iframe"),
			'HTML.AllowedAttributes'   => array("img.src", "img.alt", "img.title", "*.width", "*.height", "a.href", "a.title", "a.target", "*.style", "*.class", "iframe.frameborder", "iframe.src"),
		);
		$this->answer = $p->purify($this->answer);
		parent::afterValidate();
	}
}
