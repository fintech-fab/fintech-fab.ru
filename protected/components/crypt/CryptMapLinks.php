<?php

class CryptMapLinks extends CryptMap
{

	const MODERATOR_ACCESS = 'moderator';
	const SUBSCRIBER_CONFIRM = 'subscribeConfirm';
	const SUBSCRIBER_DISABLE = 'subscribeDisable';

	static $aMap = array(

		self::MODERATOR_ACCESS   => array(
			'route' => '/',
			'time'  => SiteParams::CTIME_HOUR,
		),

		self::SUBSCRIBER_CONFIRM => array(
			'route' => '/',
			'time'  => SiteParams::CTIME_WEEK,
		),

		self::SUBSCRIBER_DISABLE => array(
			'route' => '/',
			'time'  => SiteParams::CTIME_YEAR,
		),

	);


	public static function parse()
	{

		if (Yii::app()->site->ckey) {

			$aParams = parent::_getDecryptedParam(Yii::app()->site->ckey);

			if (!empty($aParams['mapName'])) {
				$sMapName = $aParams['mapName'];
				$sMethodName = 'init' . ucfirst($aParams['mapName']);

				return self::$sMethodName($aParams['params'], self::$aMap[$aParams['mapName']]['route']);
			}

		}

		return null;

	}


	/**
	 * ссылка для модераторского входа
	 *
	 * @param string $email
	 *
	 * @return string
	 */
	public static function keyModerator($email)
	{

		$aParams = array(
			'id'    => 0,
			'email' => $email,
		);

		return parent::_getCryptedComplexCode(self::MODERATOR_ACCESS, $aParams);

	}

	/**
	 * @param array  $aParams
	 * @param string $sRoute
	 */
	private static function initModerator($aParams, $sRoute)
	{

		Yii::app()->user->addCookieRole('moderator');
		self::getController()->flash('Теперь вы можете управлять этим сайтом');
		Yii::app()->getController()->redirect('/');

	}


	/**
	 * ссылка для подтверждения подписки
	 *
	 * @param integer $id
	 *
	 * @return string
	 */
	public static function keySubscribeConfirm($id)
	{

		$aParams = array(
			'id' => $id,
		);

		return parent::_getCryptedComplexCode(self::SUBSCRIBER_CONFIRM, $aParams);

	}

	/**
	 * переход по ссылке подтверждения подписки
	 *
	 * @param integer $id
	 *
	 * @return string
	 */
	public static function initSubscribeConfirm($aParams, $sRoute)
	{

		$id = (int)$aParams['id'];
		$oSubscriber = Subscriber::model()->findByPk($id);
		if ($oSubscriber) {
			$oSubscriber->is_confirm = 1;
			$oSubscriber->is_enabled = 1;
			$oSubscriber->save();
		}

		self::getController()->flash('Теперь вы будете получать сообщения о новых публикациях.');
		Yii::app()->getController()->redirect('/');

	}


	/**
	 * ссылка для отписки от рассылки
	 *
	 * @param integer $id
	 *
	 * @return string
	 */
	public static function keySubscribeDisable($id)
	{

		$aParams = array(
			'id' => $id,
		);

		return parent::_getCryptedComplexCode(self::SUBSCRIBER_DISABLE, $aParams);

	}

	/**
	 * переход по ссылке отписки от рассылки
	 *
	 * @param array  $aParams
	 * @param string $sRoute
	 */
	public static function initSubscribeDisable($aParams, $sRoute)
	{

		$id = (int)$aParams['id'];
		$oSubscriber = Subscriber::model()->findByPk($id);
		if ($oSubscriber) {
			$oSubscriber->is_enabled = 0;
			$oSubscriber->save();
		}

		self::getController()->flash('Сайт больше не будет отправлять тебе сообщения о новых публикациях', 'warning');
		Yii::app()->getController()->redirect('/');

	}


	/**
	 * @return Controller
	 *
	 */
	private static function getController()
	{
		return Yii::app()->getController();
	}

}


