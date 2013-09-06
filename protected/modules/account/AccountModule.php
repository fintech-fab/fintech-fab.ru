<?php
/**
 * Class AccountModule
 */
class AccountModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		Yii::app()->user->loginUrl = Yii::app()->createUrl('account/login');
		Yii::app()->user->setStateKeyPrefix('_account');

		// import the module-level models and components
		$this->setImport(array(
			'account.models.*',
			'account.components.*',
		));
	}

	/**
	 * @param CController $controller
	 * @param CAction     $action
	 *
	 * @return bool
	 */
	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action)) {
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		} else {
			return false;
		}
	}
}
