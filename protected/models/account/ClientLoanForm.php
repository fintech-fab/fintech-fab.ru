<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientLoanForm extends ClientCreateFormAbstract
{
	public $channel_type;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('channel_type', 'required', 'message' => 'Для оформления займа требуется выбрать способ его получения'),
			array('channel_type', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getClientProductsChannelsList()), 'message' => 'Для оформления займа требуется выбрать способ его получения'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('channel_type' => 'Выберите способ получения продукта')
		);
	}
}
