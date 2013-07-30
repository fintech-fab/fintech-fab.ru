<?php
class ImageController extends CController
{

	const C_TYPE_PHOTO = 'photo';
	const C_TYPE_PASSPORT_FRONT = 'passport_front';
	const C_TYPE_PASSPORT_NOTIFICATION = 'passport_notification';
	const C_TYPE_PASSPORT_LAST = 'passport_last';

	public static $aTypes = array(
		self::C_TYPE_PHOTO                 => array(
			'title'       => 'Фотография клиента',
			'description' => '',
		),
		self::C_TYPE_PASSPORT_FRONT        => array(
			'title'       => 'Паспорт - лицевая сторона',
			'description' => 'Покажите в веб-камеру первую страницу паспорта (кем выдан, дата выдачи, код подразделения) и нажмите',
		),
		self::C_TYPE_PASSPORT_NOTIFICATION => array(
			'title'       => 'Паспорт - страница регистрации',
			'description' => 'Покажите в веб-камеру страницу регистрации',
		),
		self::C_TYPE_PASSPORT_LAST         => array(
			'title'       => 'Паспорт - последняя страница',
			'description' => 'Покажите в веб-камеру последнюю страницу паспорта',
		),
	);

	/**
	 * Обработать фотографию
	 */
	public function actionProcessPhoto()
	{
		//все формы заполнены
		Yii::app()->session['form1_complete'] = true;
		Yii::app()->session['form2_complete'] = true;

		//все шаги пройдены и есть ID клиента
		if (!Yii::app()->session['form1_complete'] OR !Yii::app()->session['form2_complete'] OR is_null($this->_getClient())) {
			Yii::app()->end();
		}

		$sPhoto = Yii::app()->request->getPost('image');

		$sType = Yii::app()->request->getPost('type', null);

		if (!is_null($sPhoto)) {

			//расшифровываем полученное изображение
			$sPhoto = str_replace('data:image/png;base64,', '', $sPhoto);

			$sImage = base64_decode($sPhoto);

			//сохраняем его
			$sFilePath = Yii::app()->basePath . '/runtime/face_photos/';

			if (!file_exists($sFilePath)) {
				@mkdir($sFilePath);
			}

			$sFilePath .= time() . '.png';

			file_put_contents($sFilePath, $sImage);

			switch ($sType) {
				//фотография
				case self::C_TYPE_PHOTO:

					//вырезаем нужный квадрат
					$oImage = Yii::app()->image->load($sFilePath);

					$oImage->crop(300, 300);

					$oImage->save($sFilePath);

					//отправляем на определение лица
					$response = $this->requestToLibCCV(realpath($sFilePath));

					if (!$response) {
						echo '0';

						break;
					}

					$response = json_decode($response);

					if (!$response) {
						echo '0';

						break;
					}

					//@todo unlink фотографии

					//если лицо нашлось - загружаем
					if (count($response) == 1) {
						$this->uploadToUserDir($sImage, $sType);
					}

					echo count($response);

					break;
				//паспорт - лицевая сторона
				case self::C_TYPE_PASSPORT_FRONT:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => array(
							'id'    => self::C_TYPE_PASSPORT_NOTIFICATION,
							'title' => self::$aTypes[self::C_TYPE_PASSPORT_NOTIFICATION]['description'],
						),
					);

					echo json_encode($aResponse);

					break;
				//паспорт - страница регистрации
				case self::C_TYPE_PASSPORT_NOTIFICATION:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => array(
							'id'    => self::C_TYPE_PASSPORT_LAST,
							'title' => self::$aTypes[self::C_TYPE_PASSPORT_LAST]['description'],
						),
					);

					echo json_encode($aResponse);

					break;
				//паспорт - последняя страница
				case self::C_TYPE_PASSPORT_LAST:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => null,
					);

					echo json_encode($aResponse);

					break;
			}

		}
	}

	/**
	 * Загрузить изображение в папку пользователя
	 *
	 * @param $sImage
	 * @param $sType
	 */
	private function uploadToUserDir($sImage, $sType)
	{

		$iClientId = $this->_getClient();

		$sFilePath = Yii::app()->basePath . '/runtime/face_users/' . $iClientId;

		@mkdir($sFilePath, 0777, true);

		$sFilePath .= '/' . $sType . '.png';

		file_put_contents($sFilePath, $sImage);

		//если фотография - обрезаем нужный квадрат
		if ($sType == self::C_TYPE_PHOTO) {

			//вырезаем нужный квадрат
			$oImage = Yii::app()->image->load($sFilePath);

			$oImage->crop(300, 300);

			$oImage->save($sFilePath);

		}
	}

	/**
	 * Запрос на определение лица
	 *
	 * @param $file
	 *
	 * @return mixed
	 */
	private function requestToLibCCV($file)
	{
		$ch = curl_init('http://192.168.10.9/bbf/detect.objects?model=face');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);

		$post = array(
			"source" => "@" . $file,
			"model"  => "face",
		);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		$response = curl_exec($ch);

		return $response;
	}

	/**
	 * Получить ID клиента
	 *
	 * @return int
	 */
	private function _getClient()
	{

		$iUserId = Yii::app()->session['client_id'];

		return $iUserId;

	}
}