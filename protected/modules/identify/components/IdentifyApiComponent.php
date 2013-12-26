<?php
/**
 * Class IdentifyApiComponent
 */

class IdentifyApiComponent
{
	/**
	 * Константы
	 */
	const TMP_HASH = "390vJk!gl;6756fi&g893jn$$!13hgh"; // хэш для заглушки todo: убрать

	const С_ERROR_NONE = 0; // нет ошибки
	const С_ERROR_REQUEST_HANDLING = -1; // ошибка обработки запроса
	const С_ERROR_NONE_WITH_INSTRUCTION = 1; // ошибки нет, содержит инструкцию для дальнейших действий

	const MSG_ERROR_INVALID_REQUEST = "Некорректный запрос";

	const STEP_FACE = 1;
	const STEP_DOCUMENT1 = 2;
	const STEP_DOCUMENT2 = 3;
	const STEP_DOCUMENT3 = 4;
	const STEP_DOCUMENT4 = 5;
	const STEP_DONE = 6;

	/**
	 * @var array Инструкции к шагам
	 */
	public static $aInstructionsForSteps = array(
		self::STEP_FACE      => 'Сфотографируйтесь',
		self::STEP_DOCUMENT1 => 'Покажите документ 1',
		self::STEP_DOCUMENT2 => 'Покажите документ 2',
		self::STEP_DOCUMENT3 => 'Покажите документ 3',
		self::STEP_DOCUMENT4 => 'Покажите документ 4',
		self::STEP_DONE      => "Вы успешно прошли идентификацию. Зайдите в Личный Кабинет.",
	);

	/**
	 * @var array Заголовки шагов
	 */
	public static $aTitlesForSteps = array(
		self::STEP_FACE      => 'Лицо',
		self::STEP_DOCUMENT1 => 'Документ 1',
		self::STEP_DOCUMENT2 => 'Документ 2',
		self::STEP_DOCUMENT3 => 'Документ 3',
		self::STEP_DOCUMENT4 => 'Документ 4',
	);

	/**
	 * @var array Описания для шагов
	 */
	public static $aDescriptionsForSteps = array(
		self::STEP_FACE      => 'Пример фотографии лица',
		self::STEP_DOCUMENT1 => 'Пример фотографии документа 1',
		self::STEP_DOCUMENT2 => 'Пример фотографии документа 2',
		self::STEP_DOCUMENT3 => 'Пример фотографии документа 3',
		self::STEP_DOCUMENT4 => 'Пример фотографии документа 4',
	);

	/**
	 * @var array Примеры изображений для шагов
	 */
	public static $aExamplesForSteps = array(
		self::STEP_FACE      => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT1 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT2 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT3 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT4 => 'https://www.google.ru/images/srpr/logo11w.png',
	);

	public function init()
	{

	}

	/**
	 * @param $aRequest array() запрос
	 *
	 * @return array
	 */
	public function processRequest($aRequest)
	{
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

		$sToken = Yii::app()->adminKreddyApi->getIdentifyApiAuth($sPhone, $sPassword);

		// если не удалось авторизоваться по логину-паролю вернуть код -1.
		if (!$sToken) {
			return $this->formatErrorResponse('Не удалось авторизоваться по логину-паролю');
		}

		$iStepNumber = self::STEP_FACE;
		// авторизация успешна; генерируем соответствующий токен todo: убрать заглушку.
		$sToken = $this->generateToken($sToken, $iStepNumber);

		// ответ: ошибки нет, всё ок, посылаем дальнейшую инструкцию.
		return $this->formatResponse($sToken, array(
				'instruction' => IdentifyApiComponent::$aInstructionsForSteps[$iStepNumber],
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
		$sApiToken = Yii::app()->adminKreddyApi->updateIdentifyApiToken($sApiToken);
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
			$aResponse = $this->formatResponse($sToken,
				array(
					'document' => $iStepNumber,
					'title'       => self::$aTitlesForSteps[$iNextStepNumber],
					'instruction' => self::$aInstructionsForSteps[$iNextStepNumber],
					'example'     => self::$aExamplesForSteps[$iNextStepNumber],
					'description' => self::$aDescriptionsForSteps[$iNextStepNumber],
				)
			);
			break;

			case self::STEP_DOCUMENT4:
				$aResponse = $this->formatDoneResponse($sToken, self::$aInstructionsForSteps[$iNextStepNumber]);
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
		$sFilePath = Yii::app()->getBasePath() . "/../public/uploads/";
		if (!file_exists($sFilePath . 'identify_photos')) {
			mkdir($sFilePath . 'identify_photos');
		}

		$sFileName = /*Yii::app()->user->getId().*/
			"photo-" . $iStepNumber . ".jpg";
		$sFilePath .= '/identify_photos/' . $sFileName;

		$iFileSize = @file_put_contents($sFilePath, base64_decode($sImageBase64));

		//TODO реализовать отправку файла в API

		return $iFileSize > 0;
	}

	/**
	 * Генерирует токен с учётом текущего шага и токена пользователя
	 *
	 * @param $sApiToken хэш, идентифицирующий пользователя (токен из API)
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
}
