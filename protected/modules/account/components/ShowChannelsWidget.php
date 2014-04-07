<?php

/**
 * Class ShowChannelsWidget
 */
class ShowChannelsWidget extends CWidget
{
	const C_MOBILE = 'mobile';
	const C_CARD = 'card';

	const MSG_CHANNEL_NOT_AVAILABLE = 'Данный канал недоступен!';
	const MSG_NO_CARD_WARNING = '<b style="color: #ff0000;">ВНИМАНИЕ!</b> У Вас нет привязанной банковской карты. Для получения займов на банковскую карту пройдите процедуру привязки карты.';
	const MSG_CONFIRM_CHANNEL_PHONE = "Вы уверены, что хотите выбрать в качестве канала получения мобильный телефон? Изменить канал после подтверждения заявки нельзя!";

	const BTN_WIDTH_PX = 190;

	/**
	 * @var array названия каналов
	 */
	private static $aChannelNames = array(
		self::C_MOBILE => 'на мобильный телефон',
		self::C_CARD   => 'на банковскую карту',
	);

	/**
	 * @var array названия файлов картинок (из папки static/images/channels/
	 */
	private static $aImageNames = array(
		self::C_CARD   => 'card.png',
		self::C_MOBILE => 'mobile.png',
	);

	/**
	 * @var array каналов
	 */
	public $aAvailableChannels = array();

	/**
	 * @var string название формы, в которую вставляется виджет
	 */
	public $sFormName = "";

	/**
	 * Возвращает код кнопки для недоступного канала - банковская карта
	 *
	 * @return string
	 */
	public function getCardNotAvailableButton()
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'label'       => ('Получить займ ' . self::$aChannelNames[self::C_CARD]),
				'htmlOptions' => array(
					'disabled' => 'disabled',
					'title'    => self::MSG_CHANNEL_NOT_AVAILABLE,
					'style'    => 'width: ' . (self::BTN_WIDTH_PX - 30) . "px",
				),
			),
			true);

		return $sButton;
	}

	/**
	 * Возвращает код кнопки "отправить", имеющей значение номера канала, для доступных каналов
	 *
	 * @param      $sChannelType
	 * @param bool $mConfirm
	 *
	 * @return string
	 */
	private function getAvailableChannelSubmitButton($sChannelType, $mConfirm = false)
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'buttonType'  => 'submit',
				'type'        => 'primary',
				'label'       => ('Получить займ ' . self::$aChannelNames[$sChannelType]),
				'htmlOptions' => array(
					'value'   => $this->aAvailableChannels[$sChannelType],
					'name'    => $this->sFormName . '[channel_id]',
					'confirm' => (!empty($mConfirm) ? $mConfirm : null),
					'style'   => 'width: ' . self::BTN_WIDTH_PX . "px",

				),
			),
			true);

		return $sButton;
	}

	/**
	 * @return string
	 */
	public function getMobileSubmitButton()
	{
		return $this->getAvailableChannelSubmitButton(self::C_MOBILE, self::MSG_CONFIRM_CHANNEL_PHONE);
	}

	/**
	 * @return string
	 */
	public function getCardSubmitButton()
	{
		return $this->getAvailableChannelSubmitButton(self::C_CARD);
	}

	/**
	 * Выводит нужную картинку
	 *
	 * @param $sChannelType
	 *
	 * @return string
	 */
	private function getImage($sChannelType)
	{
		return '<img src="/static/images/channels/' . self::$aImageNames[$sChannelType] . '" style="height:100px;" class="img-polaroid"> &nbsp; ';
	}

	/**
	 * @return string
	 */
	public function getMobileImage()
	{
		return $this->getImage(self::C_MOBILE);
	}

	/**
	 * @return string
	 */
	public function getCardImage()
	{
		return $this->getImage(self::C_CARD);
	}

	/**
	 * Доступен ли канал мобильный Клиенту
	 *
	 * @return bool
	 */
	public function getIsMobileAvailable()
	{
		$bIsAvailable = ($this->aAvailableChannels[self::C_MOBILE] !== false);

		return $bIsAvailable;
	}

	/**
	 * Доступна ли канал карта Клиенту
	 *
	 * @return bool
	 */
	public function getIsCardAvailable()
	{
		$bIsAvailable = ($this->aAvailableChannels[self::C_CARD] !== false);

		return $bIsAvailable;
	}

	/**
	 * @return string
	 */
	public function getNoCardWarning()
	{
		return self::MSG_NO_CARD_WARNING;
	}

	/**
	 * @return mixed
	 */
	public function getAddCardButton()
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'url'         => Yii::app()->createUrl('account/addCard'),
				'type'        => 'danger',
				'icon'        => "icon-ok icon-white",
				'label'       => 'Привязать банковскую карту',
				'htmlOptions' => array(
					'style' => 'width: ' . (self::BTN_WIDTH_PX + 95) . "px",
				),
			), true
		);

		return $sButton;
	}

	public function run()
	{
		echo '<div class="well center">' .
			$this->getCardImage();
		if ($this->getIsCardAvailable()):
			echo $this->getCardSubmitButton();
		else:
			echo $this->getCardNotAvailableButton() .
				'<br /><br />' .
				$this->getNoCardWarning() .
				'<br /><br />' .
				$this->getAddCardButton();
		endif;
		echo '</div>';

		if ($this->getIsMobileAvailable()) :
			echo '<div class="well center">' .
				$this->getMobileImage() .
				$this->getMobileSubmitButton() .
				'</div>';
		endif;
	}
}
