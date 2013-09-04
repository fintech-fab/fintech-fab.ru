<?php
/**
 * Class AdminModule
 */
class AdminModule extends CWebModule
{
	public $ipFilters = array('127.0.0.1', '::1');

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
		//TODO доделать тему и переключить админку на неё
		//Yii::app()->themeManager->basePath = Yii::app()->basePath."/themes";
		//Yii::app()->theme = 'bootstrap';
	}

	/**
	 * @param $ip
	 *
	 * @return bool
	 */
	protected function allowIp($ip)
	{
		if (empty($this->ipFilters)) {
			return true;
		}
		foreach ($this->ipFilters as $filter) {
			if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param CController $controller
	 * @param CAction     $action
	 *
	 * @return bool
	 */
	public function beforeControllerAction($controller, $action)
	{
		Yii::app()->errorHandler->errorAction = 'admin/default/error';

		if (parent::beforeControllerAction($controller, $action)) {
			if (!$this->allowIp(Yii::app()->request->userHostAddress)) {
				Yii::app()->request->redirect(Yii::app()->homeUrl);
			}

			return true;
		}

		return false;
	}
}
