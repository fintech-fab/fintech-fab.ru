<?php
class ImageController extends CController
{

	const C_IMAGES_DIR = '/runtime/face_users/';

	const C_TYPE_PHOTO = 'photo';
	const C_TYPE_PASSPORT_FRONT_FIRST = 'passport_front_first';
	const C_TYPE_PASSPORT_FRONT_SECOND = 'passport_front_second';
	const C_TYPE_PASSPORT_NOTIFICATION = 'passport_notification';
	const C_TYPE_PASSPORT_LAST = 'passport_last';

	const C_CONFIRM_TEXT = 'Если данные хорошо читаемы, нажмите "Продолжить". Или закройте окно, нажав на кнопку "Переснять"';

	public static $aTypes = array(
		self::C_TYPE_PHOTO                 => array(
			'title'       => 'Фотография клиента',
			'description' => '',
		),
		self::C_TYPE_PASSPORT_FRONT_FIRST  => array(
			'title'        => 'Паспорт - лицевая сторона (первая часть)',
			'example'      => '/static/img/documents/example1.jpg',
			'instructions' => 'Покажите в веб-камеру первую страницу паспорта (кем выдан, дата выдачи, код подразделения) и нажмите "сфотографировать"',
			'confirm_text' => self::C_CONFIRM_TEXT,
		),
		self::C_TYPE_PASSPORT_FRONT_SECOND => array(
			'title'        => 'Паспорт - лицевая сторона (первая часть)',
			'example'      => '/static/img/documents/example2.jpg',
			'instructions' => 'Покажите в веб-камеру вторую страницу паспорта (ФИО, пол, дата рождения, место рождения) и нажмите "сфотографировать"',
			'confirm_text' => self::C_CONFIRM_TEXT,
		),
		self::C_TYPE_PASSPORT_NOTIFICATION => array(
			'title'        => 'Паспорт - страница регистрации',
			'example'      => '/static/img/documents/example3.jpg',
			'instructions' => 'Покажите в веб-камеру страницу регистрации и нажмите "сфотографировать"',
			'confirm_text' => self::C_CONFIRM_TEXT,
		),
		self::C_TYPE_PASSPORT_LAST         => array(
			'title'        => 'Паспорт - последняя страница',
			'example'      => '/static/img/documents/example4.jpg',
			'instructions' => 'Покажите в веб-камеру последнюю страницу паспорта (даже если она пуста) и нажмите "сфотографировать"',
			'confirm_text' => self::C_CONFIRM_TEXT,
		),
	);

	/**
	 * Обработать фотографию
	 */
	public function actionProcessPhoto()
	{
		/**
		 * @var Image $oImage
		 */

		//все шаги пройдены и есть ID клиента
		if (is_null($this->getTmpClient())) {
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

					//если лицо нашлось - загружаем
					if (count($response) == 1) {
						$this->uploadToUserDir($sImage, $sType);
					}

					echo count($response);

					break;
				//паспорт - лицевая сторона - 1ая часть
				case self::C_TYPE_PASSPORT_FRONT_FIRST:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => array_merge(
							array('type' => self::C_TYPE_PASSPORT_FRONT_SECOND),
							self::$aTypes[self::C_TYPE_PASSPORT_FRONT_SECOND]
						),
					);

					echo json_encode($aResponse);

					break;

				//паспорт - лицевая сторона - 2ая часть
				case self::C_TYPE_PASSPORT_FRONT_SECOND:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => array_merge(
							array('type' => self::C_TYPE_PASSPORT_NOTIFICATION),
							self::$aTypes[self::C_TYPE_PASSPORT_NOTIFICATION]
						),
					);

					echo json_encode($aResponse);

					break;

				//паспорт - страница регистрации
				case self::C_TYPE_PASSPORT_NOTIFICATION:

					$this->uploadToUserDir($sImage, $sType);

					$aResponse = array(
						'next_type' => array_merge(
							array('type' => self::C_TYPE_PASSPORT_LAST),
							self::$aTypes[self::C_TYPE_PASSPORT_LAST]
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

			//удаляем фотографию из temp директории
			@unlink($sFilePath);
		}

	}

	/**
	 * Загрузить изображение в папку пользователя
	 *
	 * @param $sImage
	 * @param $sType
	 *
	 */

	private function uploadToUserDir($sImage, $sType)
	{
		/**
		 * @var Image $oImage
		 */

		$sClientId = $this->getTmpClient();

		$sFilePath = Yii::app()->basePath . self::C_IMAGES_DIR . $sClientId;

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
	private function getTmpClient()
	{
		return Yii::app()->clientForm->getTmpClientId();
	}
}
