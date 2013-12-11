<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		// по умолчанию выдаём сообщение об ошибке
		$aResponse = array(
			'code'    => IdentifyApiComponent::ERROR_REQUEST_HANDLING,
			'message' => IdentifyApiComponent::C_ERR_NOT_POST_REQUEST,
		);

		// если не POST-запрос - вернуть код -1;
		if (Yii::app()->request->isPostRequest) {
			// Заполняем массив request
			$aRequest['token'] = Yii::app()->request->getPost('token', '');
			$aRequest['phone'] = Yii::app()->request->getPost('login', '');
			$aRequest['password'] = Yii::app()->request->getPost('password', '');
			$aRequest['image'] = Yii::app()->request->getPost('image', '');

			// посылаем запрос к API и получаем ответ
			$aResponse = Yii::app()->getModule('identify')->identifyApi->responseToRequest($aRequest);
		}

		echo CJSON::encode($aResponse);
		Yii::app()->end();
	}
}
