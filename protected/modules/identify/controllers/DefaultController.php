<?php

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
	public function init()
	{
		$this->disableDebugToolbar();
		$this->_disableLog();

		Yii::app()->errorHandler->errorAction = 'identify/default/error';
	}

	public function actionIndex()
	{
		header('Content-Type: application/json');

		// Заполняем массив request
		$aRequest['token'] = Yii::app()->request->getPost('token', '');
		$aRequest['phone'] = Yii::app()->request->getPost('login', '');
		$aRequest['password'] = Yii::app()->request->getPost('password', '');
		$aRequest['image'] = Yii::app()->request->getPost('image', '');
		$bTest = Yii::app()->request->getPost('test', false);

		// посылаем запрос к API и получаем ответ
		/** @var IdentifyModule $oModule */
		$oModule = Yii::app()->getModule('identify');
		$aResponse = $oModule->identifyApi->processRequest($aRequest, $bTest);

		echo CJSON::encode($aResponse);

		Yii::app()->end();
	}

	public function actionError()
	{
		header('Content-Type: application/json');

		/** @var IdentifyModule $oModule */
		$oModule = Yii::app()->getModule('identify');
		$aResponse = $oModule->identifyApi->formatErrorResponse();

		echo CJSON::encode($aResponse);
		Yii::app()->end();
	}
}
