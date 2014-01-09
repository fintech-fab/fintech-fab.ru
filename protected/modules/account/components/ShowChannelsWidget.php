<?php

class ShowChannelsWidget extends CWidget
{
	const TYPE_MOBILE = 'mobile';
	const TYPE_CARD = 'card';

	/**
	 * @var array глобальный массив каналов - по сути, их два: на карту и на мобильный
	 */
	public static $aUserChannelsRegexps = array(
// todo карта может быть Кредди.......
		self::TYPE_MOBILE => '/мобил/',
		self::TYPE_CARD   => '/карту/',
	);

	public static $aImageNames = array(
		self::TYPE_CARD   => 'card.png',
		self::TYPE_MOBILE => 'mobile.jpg',
	);

	private $aAvailableClientChannels = array(
		self::TYPE_CARD   => false,
		self::TYPE_MOBILE => false,
	);

	public $aAllChannels = array();

	public $aAvailableChannelKeys = array();

	public function run()
	{
		foreach (self::$aUserChannelsRegexps as $sValue => $sRegexp) {
			// проверяем, что канал доступен пользователю (по рег.выражению), перебираем все доступные каналы
			foreach ($this->aAvailableChannelKeys as $sKey) {
				$sChannelName = $this->aAllChannels[$sKey];

				if (preg_match($sRegexp, $sChannelName)) {
					$this->aAvailableClientChannels[$sValue] = $sKey;
					break;
				}
			}

		}

		$this->render('show_channels', array("aClientChannels" => $this->aAvailableClientChannels, "aImageNames" => self::$aImageNames));
	}
}
