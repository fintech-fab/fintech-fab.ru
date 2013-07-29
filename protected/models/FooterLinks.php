<?php

/**
 * This is the model class for table "tbl_footer_links".
 *
 * The followings are the available columns in table 'tbl_footer_links':
 * @property integer $link_id
 * @property string $link_order
 * @property string $link_name
 * @property string $link_title
 * @property string $link_url
 * @property string $link_content
  */
class FooterLinks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FooterLinks the static model class
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
		return 'tbl_footer_links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('link_name, link_title', 'required'),
			array('link_order', 'numerical', 'integerOnly'=>true),
			array('link_name', 'length', 'max'=>20),
			array('link_name', 'match','pattern'=>'/^[a-z][a-z0-9]+$/ui', 'message' => 'Имя может содержать только цифры и латинские символы, первым символом должна быть буква'),
			array('link_name', 'unique', 'message'=>'Ссылка должна иметь уникальное имя'),
			array('link_title', 'length', 'max'=>30),
			array('link_title', 'match','pattern'=>'/^[а-яёa-z0-9?,.!\-—: ]+$/ui', 'message' => 'Заголовок может содержать только буквы, цифры, знаки препинания и пробелы'),
			array('link_url', 'length', 'max'=>255),
			array('link_url', 'url','message'=>'Неверный формат URL, пример верного формата: http://site.ru/page'),
			array('link_content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('link_id, link_order, link_name, link_title, link_url, link_content', 'safe', 'on'=>'search'),
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
			'link_id' => 'ID ссылки',
			'link_order' => 'Порядок ссылок',
			'link_name' => 'Имя ссылки',
			'link_title' => 'Заголовок ссылки',
			'link_url' => 'URL ссылки',
			'link_content' => 'Содержимое ссылки',
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

		$criteria->compare('link_id',$this->link_id);
		$criteria->compare('link_order',$this->link_order);
		$criteria->compare('link_name',$this->link_name,true);
		$criteria->compare('link_title',$this->link_title,true);
		$criteria->compare('link_url',$this->link_url,true);
		$criteria->compare('link_content',$this->link_content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'link_order ASC',
			)
		));
	}

	protected function beforeValidate()
	{
		if(parent::beforeValidate())
		{
			$this->link_title = trim($this->link_title);
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
		$this->link_content=$p->purify($this->link_content);
		parent::afterValidate();
	}
}