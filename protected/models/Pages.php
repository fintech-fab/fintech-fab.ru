<?php

/**
 * This is the model class for table "tbl_pages".
 *
 * The followings are the available columns in table 'tbl_pages':
 * @property integer $page_id
 * @property string $page_name
 * @property string $page_title
 * @property string $page_content
 */
class Pages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pages the static model class
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
		return 'tbl_pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_name, page_title, page_content', 'required'),
			array('page_name', 'length', 'max'=>20),
			array('page_name', 'match','pattern'=>'/^[a-z0-9]+$/ui', 'message' => 'Имя может содержать только цифры и латинские символы'),
			array('page_title', 'length', 'max'=>100),
			array('page_title', 'match','pattern'=>'/^[а-яёa-z0-9?,.!\-—: ]+$/ui', 'message' => 'Заголовок может содержать только буквы, цифры, знаки препинания и пробелы'),
			array('page_name', 'unique', 'message'=>'Страница должна иметь уникальное имя'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('page_id, page_name, page_title, page_content', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'page_id' => 'ID страницы',
			'page_name' => 'Имя страницы',
			'page_title' => 'Заголовок страницы',
			'page_content' => 'Содержимое страницы',
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

		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('page_name',$this->page_name,true);
		$criteria->compare('page_title',$this->page_title,true);
		$criteria->compare('page_content',$this->page_content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeValidate()
	{
		if(parent::beforeValidate())
		{
			$this->page_title = trim($this->page_title);
		}
		return true;
	}

	protected function afterValidate()
	{
		$p = new CHtmlPurifier;
		$p->options = array(
			'Filter.YouTube'=>true,
			'HTML.AllowedElements'=>array("div","p","ul","ol","li","h3","h4","h5","h6","img","a","b","i","s","span","u","em","strong","del","blockquote","sup","sub","pre","br","hr","table","tbody","thead","tr","td","th"),
			'HTML.AllowedAttributes'=>array("img.src","img.alt","img.title","img.width","img.height","a.href","a.title","*.style","*.class"),
		);
		$this->page_content=$p->purify($this->page_content);
		parent::afterValidate();
	}
}
