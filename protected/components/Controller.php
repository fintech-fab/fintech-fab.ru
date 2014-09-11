<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	/**
	 * @var bool показывать ли верхний виджет на странице.
	 */
	public $showTopPageWidget = false;

	/**
	 * @param CAction $aAction
	 *
	 * @return bool
	 */

	protected function beforeAction($aAction)
	{
		ob_start();

		if ($sTrackingID = Yii::app()->request->getParam('TrackingID')) {
			//очистка данных из GET-запроса
			$oPurifier = new CHtmlPurifier;
			$oPurifier->options = array(
				'HTML.Allowed' => '',
			);
			$sTrackingID = $oPurifier->purify($sTrackingID);
			$sTrackingID = preg_replace('/[^a-z\d]/i', '', $sTrackingID);

			if (Yii::app()->request->cookies['TrackingID'] != $sTrackingID) {
				$cookie = new CHttpCookie('TrackingID', $sTrackingID);
				$cookie->expire = time() + SiteParams::CTIME_YEAR * 5; //поставим срок жизни куки на 5 лет, чтоб наверняка
				Yii::app()->request->cookies['TrackingID'] = $cookie;
			}
		}

		if (Yii::app()->antiBot->checkIsBanned()) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			Yii::app()->end();
		}

		return parent::beforeAction($aAction);
	}

	protected function afterAction($aAction)
	{
		parent::afterAction($aAction);

		ob_end_flush();
	}

	/**
	 * @return array
	 */
	public function filters()
	{
		return array(
			array(
				'ext.pixels.PixelFilter',
			),
		);
	}

	/**
	 * Отключить DebugToolbar
	 */
	protected function disableDebugToolbar()
	{
		foreach (Yii::app()->log->routes as $oRoute) {
			if ($oRoute instanceof YiiDebugToolbarRoute) {
				$oRoute->enabled = false;
			}
		}
	}

	protected function _disableLog()
	{

		foreach (Yii::app()->log->routes as $oRoute) {
			if ($oRoute instanceof CWebLogRoute || $oRoute instanceof YiiDebugToolbarRoute) {
				$oRoute->enabled = false;
			}
		}
	}

	protected function _disableFileLog()
	{

		foreach (Yii::app()->log->routes as $oRoute) {
			if ($oRoute instanceof CFileLogRoute) {
				$oRoute->enabled = false;
			}
		}
	}


}
