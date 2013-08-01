<?php
/**
 * @property CPhpMessageSource $coreMessages
 *
 * @property CDbConnection $db
 * @property CDbConnection $db_test
 * @property CDbConnection $db_export
 * @property CDbConnection $db_export_test
 * @property CDbConnection $db_1c
 * @property CDbConnection $db_log
 * @property CDbConnection $db_clb
 * @property CDbConnection $db_clb_test
 * 
 *
 * @property CPhpMessageSource $messages
 * @property CErrorHandler $errorHandler
 * @property CSecurityManager $securityManager
 * @property CStatePersister $statePersister
 * @property CUrlManager $urlManager
 * @property CHttpRequest $request
 * @property SiteParams $site
 *
 *
 * @property CHttpSession $session
 * @property CAssetManager $assetManager
 * @property CThemeManager $themeManager
 * @property CClientScript $clientScript
 * @property CWidgetFactory $widgetFactory
 *
 * @property Bootstrap $bootstrap

 *
 * @property CDbAuthManager $authManager
 * @property CDummyCache $cache
 * @property CLogRouter $log
 *
 */
class IdpApplication extends CWebApplication
{
}

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
