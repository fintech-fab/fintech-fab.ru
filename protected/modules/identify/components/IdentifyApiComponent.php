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
		// некорректный POST-запрос - вернуть код -1;
		if (empty($aRequest['token'])
			&& empty($aRequest['phone'])
			&& empty($aRequest['password'])
			&& empty($aRequest['image'])
		) {
			return $this->getErrorResponse();
		}

		$sToken = $aRequest['token'];

		if (empty($sToken)) {
			// если это запрос без токена - значит, это запрос на авторизацию.
			$aResponse = $this->getResponseToAuth($aRequest);
		} else {
			$aResponse = $this->getResponseToToken($aRequest, $sToken);
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
	private function getResponseToAuth($aRequest)
	{
		$sPhone = $aRequest['phone'];
		$sPassword = $aRequest['password'];

		// проверить, что содержит логин и пароль, иначе вернуть код -1;
		if (empty($sPhone) || empty($sPassword)) {
			return $this->getErrorResponse('Укажите логин и пароль');
		}

		$bAuth = $this->getIsClientAuth($sPhone, $sPassword);

		// если не удалось авторизоваться по логину-паролю вернуть код -1.
		if (!$bAuth) {
			return $this->getErrorResponse('Не удалось авторизоваться по логину-паролю');
		}

		// авторизация успешна; генерируем соответствующий токен todo: убрать заглушку.
		$iStepNumber = self::STEP_FACE;
		$sToken = $this->generateToken(self::TMP_HASH, $iStepNumber);

		// ответ: ошибки нет, всё ок, посылаем дальнейшую инструкцию.
		return $this->getNoErrorResponse($sToken,
			array(
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
	private function getResponseToToken($aRequest, $sToken)
	{
		$aData = $this->decryptToken($sToken);
		$sUserHash = !empty($aData['0']) ? $aData['0'] : false;
		$iStepNumber = !empty($aData['1']) ? $aData['1'] : false;

		// ошибка в данных из токена
		if ($sUserHash === false || $iStepNumber === false) {
			return $this->getErrorResponse('Ошибка в данных из токена');
		}

		// если хэш неверный - ошибка. todo: убрать заглушку
		if ($sUserHash !== self::TMP_HASH) {
			return $this->getErrorResponse('Ошибка в данных из токена');
		}

		$sImageBase64 = !empty($aRequest['image']) ? $aRequest['image'] : false;

		// нет картинки в ПОСТ-запросе или это не картинка
		if ($sImageBase64 === false || !$this->getIsImage($sImageBase64)) {
			return $this->getErrorResponse('Некорректное изображение');
		}

		// если не получилось сохранить изображение - ошибка
		if (!$this->saveImage($sUserHash, $sImageBase64, $iStepNumber)) {
			return $this->getErrorResponse('Не удалось сохранить изображение');
		}

		// получаем ответ исходя из номера шага.
		return $this->getResponseByStep($iStepNumber, $sUserHash);
	}

	/**
	 * Авторизация в API по логину (номер телефона) и паролю
	 *
	 * @param $sPhone
	 * @param $sPassword
	 *
	 * @return bool
	 */
	private function getIsClientAuth($sPhone, $sPassword)
	{
		// тестовые данные todo: убрать заглушку
		return ($sPhone === "9513570000" && $sPassword === "Aa12345");
	}

	/**
	 * Получаем ответ исходя из номера шага.
	 *
	 * @param $iStepNumber номер шага
	 * @param $sUserHash
	 *
	 * @return array
	 */
	private function getResponseByStep($iStepNumber, $sUserHash)
	{
		switch ($iStepNumber) {
			case self::STEP_FACE:
			case self::STEP_DOCUMENT1:
			case self::STEP_DOCUMENT2:
			case self::STEP_DOCUMENT3:
			case self::STEP_DOCUMENT4:
			$sToken = $this->generateToken($sUserHash, $iStepNumber + 1);

			return $this->getNoErrorResponse($sToken,
				array(
					'document' => $iStepNumber,
					'title'       => self::$aTitlesForSteps[$iStepNumber],
						'instruction' => self::$aInstructionsForSteps[$iStepNumber],
						'example'     => self::$aExamplesForSteps[$iStepNumber],
						'description' => self::$aDescriptionsForSteps[$iStepNumber],
					)
				);
				break;

			case self::STEP_DONE:
				$sToken = $this->generateToken($sUserHash, $iStepNumber + 1);

				return $this->getDoneResponse($sToken, self::$aInstructionsForSteps[$iStepNumber]);
				break;

			default:
				$aResponse = $this->getErrorResponse();
				break;
		}


		return $aResponse;
	}

	/**
	 * Возвращает сообщение об ошибке
	 *
	 * @param $sErrorMessage
	 *
	 * @return array
	 */
	public function getErrorResponse($sErrorMessage = "")
	{
		return array(
			'code'    => IdentifyApiComponent::С_ERROR_REQUEST_HANDLING,
			'message' => (empty($sErrorMessage)) ? IdentifyApiComponent::MSG_ERROR_INVALID_REQUEST : $sErrorMessage,
		);
	}

	/**
	 * @param string  $sToken
	 * @param array() $aResult
	 *
	 * @return array
	 */
	private function getNoErrorResponse($sToken, $aResult = array())
	{
		return array(
			'code'   => IdentifyApiComponent::С_ERROR_NONE,
			'result' => array_merge(array(
				'token' => $sToken,
			), $aResult),
		);
	}

	/**
	 * @param $sToken
	 * @param $sInstruction
	 *
	 * @return array
	 */
	private function getDoneResponse($sToken, $sInstruction)
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
	 * @param $sUserHash
	 * @param $sImageBase64
	 * @param $iStepNumber
	 *
	 * @return bool
	 */
	private function saveImage($sUserHash, $sImageBase64, $iStepNumber)
	{
		//todo: убрать заглушку
		return true;
	}

	/**
	 * Генерирует токен с учётом текущего шага и идентификатора пользователя
	 *
	 * @param $sUserHash   хэш, идентифицирующий пользователя
	 * @param $iStepNumber номер следующего щага
	 *
	 * @return string
	 */
	private function generateToken($sUserHash, $iStepNumber)
	{
		return CryptArray::encrypt(
			array(
				'id' => $sUserHash, 'step' => $iStepNumber
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
