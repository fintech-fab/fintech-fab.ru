<?php

/**
 * @property CPhpMessageSource          $coreMessages
 *
 * @property CDbConnection              $db
 *
 *
 * @property CPhpMessageSource          $messages
 * @property CErrorHandler              $errorHandler
 * @property CSecurityManager           $securityManager
 * @property CStatePersister            $statePersister
 * @property CUrlManager                $urlManager
 * @property CHttpRequest               $request
 * @property SiteParams                 $siteParams
 *
 *
 * @property CHttpSession               $session
 * @property CAssetManager              $assetManager
 * @property CThemeManager              $themeManager
 * @property CClientScript              $clientScript
 * @property CWidgetFactory             $widgetFactory
 *
 * @property Bootstrap                  $bootstrap
 * @property ClientFormComponent        $clientForm
 * @property ProductsChannelsComponent  $productsChannels
 * @property AdminKreddyApiComponent    $adminKreddyApi
 * @property AntiBotComponent           $antiBot
 * @property TornadoApiComponent        $tornadoApi
 *
 *
 * @property CDbAuthManager             $authManager
 * @property CDummyCache                $cache
 * @property CLogRouter                 $log
 * @property User                       $user
 *
 */
class IdpApplication extends CWebApplication
{
}

/**
 * Class Yii
 */
class Yii extends YiiBase
{
	/**
	 * @return IdpApplication
	 */
	public static function app()
	{
		return parent::app();
	}
}
