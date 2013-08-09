<?php
class ApiController extends CController
{

	/**
	 * Получить анкеты и изображения клиентов
	 */
	public function actionClientsNew()
	{

		$aClients = ClientData::model()
			->findAllByAttributes(array(
				'complete'       => 1,
				'flag_processed' => 0
			));

		$aOutput = array();

		foreach ($aClients as $oClient) {

			//фотография и документы, загруженные клиентом
			$aImages = array();

			foreach (ImageController::$aTypes as $sTypeName => $aType) {

				$sImagePath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . '/' . $oClient->client_id . '/' . $sTypeName . '.png';

				$aImages[$sTypeName] = (file_exists($sImagePath)) ? base64_encode(file_get_contents($sImagePath)) : null;

			}

			$aOutput[] = array_merge($oClient->getAttributes(), $aImages);

		}

		$this->renderJSON($aOutput);

	}

	/**
	 * Отметить анкеты как обработанные системой Кредди
	 */
	public function actionMarkProcessed()
	{

		$aClientIds = explode(',', Yii::app()->request->getPost('clientIds'));

		foreach ($aClientIds as $iClientId) {

			ClientData::model()
				->updateByPk($iClientId, array(
					'flag_processed' => 1,
				));

		}

		$this->renderJSON(array(
			'result' => 'success',
		));

	}

	/**
	 * @param $aData
	 */
	private function renderJSON($aData)
	{

		header('Content-type: application/json');

		echo CJSON::encode($aData);

		Yii::app()->end();
	}

	public function beforeAction($oAction)
	{
		if(!Yii::app()->siteParams->isLocalServer()){
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			Yii::app()->end();
		}
		return parent::beforeAction($oAction);
	}

}