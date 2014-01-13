<?php

class ShowChannelsWidget extends CWidget
{
	const C_MOBILE = 'mobile';
	const C_CARD = 'card';

	const MSG_CHANNEL_NOT_AVAILABLE = 'Данный канал недоступен!';

	const MSG_CONFIRM_CHANNEL_PHONE = "Вы уверены, что хотите выбрать в качестве канала получения мобильный телефон? Изменить канал после подтверждения заявки нельзя!";

	const BTN_WIDTH_PX = 190;

	public static $aChannels = array(
		self::C_MOBILE,
		self::C_CARD,
	);

	/**
	 * @var array названия каналов
	 */
	public static $aChannelNames = array(
		self::C_MOBILE => 'на мобильный телефон',
		self::C_CARD   => 'на банковскую карту',
	);

	/**
	 * @var array массив каналов с рег.выражениями - на карту и на мобильный
	 */
	public static $aChannelsRegexps = array(
		self::C_MOBILE => '/мобил/',
		self::C_CARD => '/карт/',
	);

	/**
	 * @var array названия файлов картинок (из папки static/images/channels/
	 */
	public static $aImageNames = array(
		self::C_CARD   => 'card.png',
		self::C_MOBILE => 'mobile.png',
	);

	/**
	 * @var array коды каналов, если доступны, либо false - если недоступны
	 */
	private $aAvailableChannelValues = array(
		self::C_CARD   => false,
		self::C_MOBILE => false,
	);

	/**
	 * @var array все каналы, доступные для данного пакета
	 */
	public $aAllChannelNames = array();

	/**
	 * @var array ключи каналов, доступных данному пользователю
	 */
	public $aAvailableChannelKeys = array();

	/**
	 * @var string название формы, в которую вставляется виджет
	 */
	public $sFormName = "";

	/**
	 * Возвращает код кнопки для недоступного канала
	 *
	 * @param $sChannelType
	 *
	 * @return string
	 */
	public function getNotAvailableChannelButton($sChannelType)
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'label'       => ('Получить займ ' . self::$aChannelNames[$sChannelType]),
				'htmlOptions' => array(
					'disabled' => 'disabled',
					'title'    => self::MSG_CHANNEL_NOT_AVAILABLE,
					'style' => 'width: ' . (self::BTN_WIDTH_PX - 30) . "px",
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
	public function getAvailableChannelSubmitButton($sChannelType, $mConfirm = false)
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'buttonType'  => 'submit',
				'type'        => 'primary',
				'label'       => ('Получить займ ' . self::$aChannelNames[$sChannelType]),
				'htmlOptions' => array(
					'value' => $this->aAvailableChannelValues[$sChannelType],
					'name'  => $this->sFormName . '[channel]',
					'confirm' => (!empty($mConfirm) ? $mConfirm : null),
					'style' => 'width: ' . self::BTN_WIDTH_PX . "px",

				),
			),
			true);

		return $sButton;
	}

	/**
	 * Выводит нужную картинку
	 *
	 * @param $sChannelType
	 *
	 * @return string
	 */
	public function getImage($sChannelType)
	{
		return
			'<img src="/static/images/channels/' . self::$aImageNames[$sChannelType] . '" style="height:100px;" class="img-polaroid">
		&nbsp;';
	}

	/**
	 * Доступен ли канал Клиенту
	 *
	 * @param $sChannelType
	 *
	 * @return bool
	 */
	public function getIsChannelAvailable($sChannelType)
	{
		return ($this->aAvailableChannelValues[$sChannelType] !== false);
	}

	/**
	 * Заполняет массив значений доступных каналов соответствующими id канала
	 */
	private function setAvailableChannelValues()
	{
		foreach (self::$aChannels as $sChannel) {
			$sRegexp = self::$aChannelsRegexps[$sChannel];

			// перебираем все доступные Клиенту каналы
			foreach ($this->aAvailableChannelKeys as $sKey) {
				// берём соответствующее имя из массива имён каналов
				$sAvailableChannelName = $this->aAllChannelNames[$sKey];

				if (preg_match($sRegexp, $sAvailableChannelName)) {
					$this->aAvailableChannelValues[$sChannel] = $sKey;
					break;
				}
			}
		}
	}

	public function run()
	{
		// заполняем массив значений каналов
		$this->setAvailableChannelValues();

		$this->render('show_channels');
	}
}
