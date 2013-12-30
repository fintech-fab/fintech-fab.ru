<?php
/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->disableDebugToolbar();
		$this->_disableLog();

		header('Content-Type: application/json');

		// Заполняем массив request
		$aRequest['token'] = Yii::app()->request->getPost('token', '');
		$aRequest['phone'] = Yii::app()->request->getPost('login', '');
		$aRequest['password'] = Yii::app()->request->getPost('password', '');
		$aRequest['image'] = Yii::app()->request->getPost('image', '');

		// посылаем запрос к API и получаем ответ
		$aResponse = Yii::app()->getModule('identify')->identifyApi->processRequest($aRequest);

		echo CJSON::encode($aResponse);

		Yii::app()->end();
	}
}
