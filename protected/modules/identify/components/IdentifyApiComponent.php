<?php
/**
 * Class IdentifyApiComponent
 *
 * @property bool bTest
 */

class IdentifyApiComponent
{
	/**
	 * Константы
	 */

	const С_ERROR_NONE = 0; // нет ошибки
	const С_ERROR_REQUEST_HANDLING = -1; // ошибка обработки запроса
	const С_ERROR_NONE_WITH_INSTRUCTION = 1; // ошибки нет, содержит инструкцию для дальнейших действий

	const MSG_ERROR_INVALID_REQUEST = "Некорректный запрос";

	const STEP_FACE = 1;
	const STEP_DOCUMENT1 = 2;
	const STEP_DOCUMENT2 = 3;
	const STEP_DOCUMENT3 = 4;
	const STEP_DOCUMENT4 = 5;
	const STEP_DOCUMENT5 = 6;
	const STEP_DONE = 7;

	const C_TYPE_PHOTO = 'photo';

	const C_TYPE_PASSPORT_FRONT_FIRST = 'passport_front_first';
	const C_TYPE_PASSPORT_FRONT_SECOND = 'passport_front_second';
	const C_TYPE_PASSPORT_NOTIFICATION = 'passport_notification';
	const C_TYPE_PASSPORT_LAST = 'passport_last';
	const C_TYPE_DOCUMENT = 'document';

	public $bTest = false;

	public static $aIdentifySteps = array(
		self::STEP_FACE      => array(
			'type'        => self::C_TYPE_PHOTO,
			'instruction' => 'Сфотографируйтесь',
			'title'       => 'Фотография лица',
		),
		self::STEP_DOCUMENT1 => array(
			'type'        => self::C_TYPE_PASSPORT_FRONT_FIRST,
			'instruction' => 'Сфотографируйте лицевую сторону паспорта (с информацией о дате выдачи)',
			'title'       => 'Паспорт - лицевая сторона (первая часть)',
			'description' => 'Пример фотографии лицевой стороны паспорта',
			'example'     => '/images/documents/example1.jpg',
		),
		self::STEP_DOCUMENT2 => array(
			'type'        => self::C_TYPE_PASSPORT_FRONT_SECOND,
			'instruction' => 'Сфотографируйте лицевую сторону паспорта (с Вашей фотографией, ФИО и т.д.)',
			'title'       => 'Паспорт - лицевая сторона (вторая часть)',
			'description' => 'Пример фотографии лицевой стороны паспорта',
			'example'     => '/images/documents/example2.jpg',
		),
		self::STEP_DOCUMENT3 => array(
			'type'        => self::C_TYPE_PASSPORT_NOTIFICATION,
			'instruction' => 'Сфотографируйте страницу паспорта с информацией о месте регистрации',
			'title'       => 'Паспорт - страница регистрации',
			'description' => 'Пример фотографии страницы паспорта с регистрацией',
			'example'     => '/images/documents/example3.jpg',
		),
		self::STEP_DOCUMENT4 => array(
			'type'        => self::C_TYPE_PASSPORT_LAST,
			'instruction' => 'Сфотографируйте последнюю страницу паспорта (с информацией о выданных документах), даже если она пуста.',
			'title'       => 'Паспорт - последняя страница',
			'description' => 'Пример фотографии страницы паспорта с информацией о документах',
			'example'     => '/images/documents/example4.jpg',
		),
		self::STEP_DOCUMENT5 => array(
			'type'        => self::C_TYPE_DOCUMENT,
			'instruction' => 'Сфотографируйте второй документ (ИНН, заграничный паспорт, пенсионное удостоверение, водительское удостоверение, заграничный паспорт, военный билет, страховое свидетельство государственного пенсионного страхования',
			'title'       => 'Второй документ',
			'description' => 'Пример фотографии второго документа',
			'example'     => '/images/documents/second.jpg',
		),
		self::STEP_DONE      => array(
			'instruction' => 'Вы успешно прошли идентификацию. Зайдите в Личный Кабинет.',
			'title'       => 'Идентификация успешно завершена!',
			'description' => 'Идентификация успешно завершена!',
		),
	);

	public function init()
	{

	}

	/**
	 * @param      $aRequest array() запрос
	 *
	 * @param bool $bTest
	 *
	 * @return array
	 */
	public function processRequest($aRequest, $bTest = false)
	{
		$this->bTest = $bTest;

		// некорректный POST-запрос - ошибка
		if (empty($aRequest['token']) && empty($aRequest['image'])
			&& empty($aRequest['phone']) && empty($aRequest['password'])
		) {
			$aResponse = $this->formatErrorResponse();
		} elseif (empty($aRequest['token'])) {
			// если это запрос без токена - значит, это запрос на авторизацию
			$aResponse = $this->getAuth($aRequest);
		} else {
			$aResponse = $this->getProcessResult($aRequest, $aRequest['token']);
		}

		return $aResponse;
	}

	/**
	 * Возвращает ответ на запрос авторизации
	 *
	 * @param $aRequest
	 *
	 * @return array
	 */
	private function getAuth($aRequest)
	{
		$sPhone = $aRequest['phone'];
		$sPassword = $aRequest['password'];

		// проверить, что содержит логин и пароль, иначе вернуть код -1;
		if (empty($sPhone) || empty($sPassword)) {
			return $this->formatErrorResponse('Укажите логин и пароль');
		}

		$sApiToken = Yii::app()->adminKreddyApi->getIdentifyApiAuth($sPhone, $sPassword, $this->bTest);

		// если не удалось авторизоваться по логину-паролю вернуть код -1.
		if (!$sApiToken) {
			return $this->formatErrorResponse('Не удалось авторизоваться.');
		}

		$iStepNumber = self::STEP_FACE;
		// авторизация успешна; генерируем соответствующий токен todo: убрать заглушку.
		$sToken = $this->generateToken($sApiToken, $iStepNumber);

		// ответ: ошибки нет, всё ок, посылаем дальнейшую инструкцию.
		return $this->formatResponse($sToken, array(
				'instruction' => $this->getInstructionByStep($iStepNumber),
			)
		);
	}

	/**
	 * Возвращает ответ на запрос с токеном
	 *
	 * @param $aRequest
	 * @param $sToken
	 *
	 * @return array
	 */
	private function getProcessResult($aRequest, $sToken)
	{
		$aData = $this->decryptToken($sToken);

		$sApiToken = !empty($aData['0']) ? $aData['0'] : false;
		$iStepNumber = !empty($aData['1']) ? $aData['1'] : false;

		// ошибка в данных из токена
		if ($sApiToken === false || $iStepNumber === false) {
			return $this->formatErrorResponse('Ошибка в данных из токена');
		}

		// обновляем токен в API (заодно проверяется его корректность)
		$sApiToken = Yii::app()->adminKreddyApi->updateIdentifyApiToken($sApiToken, $this->bTest);
		if (!$sApiToken) {
			return $this->formatErrorResponse('Ошибка авторизации! Возможно, закончилась сессия.');
		}

		$sImageBase64 = !empty($aRequest['image']) ? $aRequest['image'] : false;

		// нет картинки в ПОСТ-запросе или это не картинка
		if ($sImageBase64 === false || !$this->getIsImage($sImageBase64)) {
			return $this->formatErrorResponse('Некорректное изображение');
		}

		// если не получилось сохранить изображение - ошибка
		if (!$this->saveImage($sApiToken, $sImageBase64, $iStepNumber)) {
			return $this->formatErrorResponse('Не удалось сохранить изображение');
		}

		// получаем ответ исходя из номера шага.
		return $this->getProcessResultByStep($iStepNumber, $sApiToken);
	}

	/**
	 * Возвращает ответ согласно номеру текущего шага.
	 *
	 * @param $iStepNumber номер шага
	 * @param $sApiToken
	 *
	 *
	 * @return array
	 */
	private function getProcessResultByStep($iStepNumber, $sApiToken)
	{
		$iNextStepNumber = (int)$iStepNumber + 1;
		$sToken = $this->generateToken($sApiToken, $iNextStepNumber);

		switch ($iStepNumber) {
			case self::STEP_FACE:
			case self::STEP_DOCUMENT1:
			case self::STEP_DOCUMENT2:
			case self::STEP_DOCUMENT3:
			case self::STEP_DOCUMENT4:
				$aResponse = $this->formatResponse($sToken,
					array(
						'document'    => $iStepNumber,
						'title'       => $this->getTitleByStep($iNextStepNumber),
						'instruction' => $this->getInstructionByStep($iNextStepNumber),
						'example'     => $this->getExampleByStep($iNextStepNumber),
						'description' => $this->getDescriptionByStep($iNextStepNumber),
					)
				);
				break;

			case self::STEP_DOCUMENT5:
				if (!Yii::app()->adminKreddyApi->setFinishedVideoId($sApiToken, $this->bTest)) {
					return $this->formatErrorResponse('Не удалось завершить идентификацию!');
				}
				$aResponse = $this->formatDoneResponse($sToken, $this->getInstructionByStep($iNextStepNumber));
				break;

			default:
				$aResponse = $this->formatErrorResponse();
				break;
		}


		return $aResponse;
	}

	/**
	 * @param string  $sToken
	 * @param array() $aResult
	 *
	 * @return array
	 */
	private function formatResponse($sToken, $aResult = array())
	{
		return array(
			'code'   => IdentifyApiComponent::С_ERROR_NONE,
			'result' => array_merge(array(
				'token' => $sToken,
			), $aResult),
		);
	}

	/**
	 * Возвращает сообщение об ошибке (без токена)
	 *
	 * @param $sErrorMessage
	 *
	 * @return array
	 */
	public function formatErrorResponse($sErrorMessage = "")
	{
		return array(
			'code'    => IdentifyApiComponent::С_ERROR_REQUEST_HANDLING,
			'message' => (empty($sErrorMessage)) ? IdentifyApiComponent::MSG_ERROR_INVALID_REQUEST : $sErrorMessage,
		);
	}

	/**
	 * Возвращает сообщение о том, что идентификация пройдена
	 *
	 * @param $sToken
	 * @param $sInstruction
	 *
	 * @return array
	 */
	private function formatDoneResponse($sToken, $sInstruction)
	{
		return array(
			'code'   => IdentifyApiComponent::С_ERROR_NONE,
			'result' => array(
				'token'       => $sToken,
				'done'        => 1,
				'instruction' => $sInstruction,
			)
		);
	}

	/**
	 * Проверка, картинка ли: в случае если удалось получить размер - это изображение.
	 *
	 * @param $sImageBase64
	 *
	 * @return bool
	 */
	private function getIsImage($sImageBase64)
	{
		$sImage = base64_decode($sImageBase64);
		$oImageSize = @getimagesizefromstring($sImage) or false;

		return ($oImageSize !== false);
	}

	/**
	 * Сохраняет изображение на сервер идентификации
	 *
	 * @param $sApiToken
	 * @param $sImageBase64
	 * @param $iStepNumber
	 *
	 * @return bool
	 */
	private function saveImage($sApiToken, $sImageBase64, $iStepNumber)
	{

		$sFilePath = Yii::app()->getBasePath() . "/runtime/";
		//создаем дирекиторию, если ее не существует
		if (!file_exists($sFilePath . 'identify_photos')) {
			mkdir($sFilePath . 'identify_photos');
		}

		$sFileName = md5($sApiToken) . "-photo-" . $iStepNumber . ".jpg";
		$sFilePath .= 'identify_photos/' . $sFileName;

		//сохраняем данные в файл
		$iFileSize = @file_put_contents($sFilePath, base64_decode($sImageBase64));

		$bResult = false;

		//если удалось сохранить данные в файл, отправляем данные через API admin.kreddy
		if ($iFileSize > 0) {
			/** @noinspection PhpUndefinedFunctionInspection */
			$oCurlFile = curl_file_create($sFilePath, 'image/jpeg', $sFileName);
			$sType = $this->getTypeByStep($iStepNumber);
			$bResult = Yii::app()->adminKreddyApi->uploadDocument($oCurlFile, $sType, $sApiToken, $this->bTest);
		}

		if (file_exists(realpath($sFilePath))) {
			unlink(realpath($sFilePath));
		}

		return $bResult;
	}

	/**
	 * Генерирует токен с учётом текущего шага и токена пользователя
	 *
	 * @param $sApiToken   хэш, идентифицирующий пользователя (токен из API)
	 * @param $iStepNumber номер следующего щага
	 *
	 * @return string
	 */
	private function generateToken($sApiToken, $iStepNumber)
	{
		return CryptArray::encrypt(
			array(
				'apiToken' => $sApiToken, 'step' => $iStepNumber
			)
		);
	}

	/**
	 * Расшифровывает токен
	 *
	 * @param $sToken
	 *
	 * @return array
	 */
	private function decryptToken($sToken)
	{
		return CryptArray::decrypt($sToken);
	}

	/**
	 * @param $iStepNumber
	 *
	 * @return mixed
	 */
	private function getTypeByStep($iStepNumber)
	{
		return isset(self::$aIdentifySteps[$iStepNumber]['type'])
			? self::$aIdentifySteps[$iStepNumber]['type']
			: null;
	}

	/**
	 * @param $iStepNumber
	 *
	 * @return null
	 */
	private function getInstructionByStep($iStepNumber)
	{
		return isset(self::$aIdentifySteps[$iStepNumber]['instruction'])
			? self::$aIdentifySteps[$iStepNumber]['instruction']
			: null;
	}

	/**
	 * @param $iStepNumber
	 *
	 * @return null
	 */
	private function getTitleByStep($iStepNumber)
	{
		return isset(self::$aIdentifySteps[$iStepNumber]['title'])
			? self::$aIdentifySteps[$iStepNumber]['title']
			: null;
	}

	/**
	 * @param $iStepNumber
	 *
	 * @return null
	 */
	private function getDescriptionByStep($iStepNumber)
	{
		return isset(self::$aIdentifySteps[$iStepNumber]['description'])
			? self::$aIdentifySteps[$iStepNumber]['description']
			: null;
	}

	/**
	 * @param $iStepNumber
	 *
	 * @return null
	 */
	private function getExampleByStep($iStepNumber)
	{
		return isset(self::$aIdentifySteps[$iStepNumber]['example'])
			? self::$aIdentifySteps[$iStepNumber]['example']
			: null;
	}
}
