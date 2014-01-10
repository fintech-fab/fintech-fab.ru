<?php

class ShowChannelsWidget extends CWidget
{
	const C_MOBILE = 'mobile';
	const C_CARD = 'card';

	const MSG_CHANNEL_NOT_AVAILABLE = 'Данный канал недоступен!';

	const MSG_CONFIRM_CHANNEL_PHONE = "Вы уверены, что хотите выбрать в качестве канала получения мобильный телефон? Изменить канал после подтверждения заявки нельзя!";

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
		self::C_CARD   => '/карту/',
	);

	/**
	 * @var array названия файлов картинок (из папки static/images/channels/
	 */
	public static $aImageNames = array(
		self::C_CARD   => 'card.png',
		self::C_MOBILE => 'mobile.jpg',
	);

	/**
	 * @var array коды каналов, если доступны, либо false - если недоступны
	 */
	private $aAvailableChannelCodes = array(
		self::C_CARD   => false,
		self::C_MOBILE => false,
	);

	/**
	 * @var array все каналы, доступные для данного пакета
	 */
	public $aAllChannels = array();

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
	 * @param $sLabelWhere название канала ("на банковскую карту")
	 *
	 * @return string
	 */
	public function getNotAvailableChannelButton($sLabelWhere)
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'label'       => ('Получить займ ' . $sLabelWhere),
				'htmlOptions' => array(
					'disabled' => 'disabled',
					'title'    => self::MSG_CHANNEL_NOT_AVAILABLE,
				),
			)
			, true);

		return $sButton;
	}

	/**
	 * Возвращает код кнопки "отправить", имеющей значение номера канала, для доступных каналов
	 *
	 * @param      $sLabelWhere
	 * @param      $sValue
	 * @param bool $mConfirm
	 *
	 * @return string
	 */
	public function getAvailableChannelSubmitButton($sLabelWhere, $sValue, $mConfirm = false)
	{
		$sButton = $this->widget('bootstrap.widgets.TbButton',
			array(
				'buttonType'  => 'submit',
				'type'        => 'primary',
				'label'       => ('Получить займ ' . $sLabelWhere),
				'htmlOptions' => array(
					'value'   => $sValue,
					'name'    => $this->sFormName . '_channel',
					'confirm' => (!empty($mConfirm) ? $mConfirm : null),
				),
			)
			, true);

		return $sButton;
	}

	public function run()
	{
		foreach ($this->aAvailableChannelKeys as $sKey) {
			$sChannelName = $this->aAllChannels[$sKey];
			if (preg_match(self::$aChannelsRegexps[self::C_MOBILE], $sChannelName)) {
				$this->aAvailableChannelCodes[self::C_MOBILE] = $sKey;
			} elseif (preg_match(self::$aChannelsRegexps[self::C_CARD], $sChannelName)) {
				$this->aAvailableChannelCodes[self::C_CARD] = $sKey;
			}
		}

		$this->render('show_channels',
			array(
				"aChannelsIfAvailable" => $this->aAvailableChannelCodes,
				"aChannelNames"        => self::$aChannelNames,
				"aImageNames"          => self::$aImageNames,
				'sFormName'            => $this->sFormName,
			)
		);
	}
}
