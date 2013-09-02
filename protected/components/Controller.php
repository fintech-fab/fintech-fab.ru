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

	protected function beforeAction($aAction)
	{

		if ($sTrackingID = Yii::app()->request->getParam('TrackingID')) {
			//очистка данных из GET-запроса
			$oPurifier = new CHtmlPurifier;
			$oPurifier->options = array(
				'HTML.Allowed' => '',
			);
			$sTrackingID = $oPurifier->purify($sTrackingID);
			$sTrackingID = preg_replace('/\s+/', '', $sTrackingID);
			$sTrackingID = preg_replace('/[^\d]/', '', $sTrackingID);

			if (Yii::app()->request->cookies['TrackingID'] != $sTrackingID) {
				$cookie = new CHttpCookie('TrackingID', $sTrackingID);
				$cookie->expire = time() + 60 * 60 * 24;
				Yii::app()->request->cookies['TrackingID'] = $cookie;
			}
		}

		if (Yii::app()->antiBot->checkIsBanned()) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			Yii::app()->end();
		}

		return parent::beforeAction($aAction);
	}
}
