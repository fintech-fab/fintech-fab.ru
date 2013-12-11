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

	const ERROR_NONE = 0; // нет ошибки
	const ERROR_REQUEST_HANDLING = -1; // ошибка обработки запроса
	const ERROR_NONE_WITH_INSTRUCTION = 1; // ошибки нет, содержит инструкцию для дальнейших действий

	const C_ERR_NOT_POST_REQUEST = "Некорректный запрос";

	const STEP_FACE = 1;
	const STEP_DOCUMENT1 = 2;
	const STEP_DOCUMENT2 = 3;
	const STEP_DOCUMENT3 = 4;
	const STEP_DOCUMENT4 = 5;
	const STEP_DOCUMENT5 = 6;

	public static $aInstructionsForSteps = array(
		self::STEP_FACE      => 'Сфотографируйтесь',
		self::STEP_DOCUMENT1 => 'Покажите документ1',
		self::STEP_DOCUMENT2 => 'Покажите документ2',
		self::STEP_DOCUMENT3 => 'Покажите документ3',
		self::STEP_DOCUMENT4 => 'Покажите документ4',
		self::STEP_DOCUMENT5 => "Вы успешно прошли идентификацию. Зайдите в Личный Кабинет.",
	);

	public static $aTitlesForSteps = array(
		self::STEP_FACE      => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT1 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT2 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT3 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT4 => 'https://www.google.ru/images/srpr/logo11w.png',
	);

	public static $aDescriptionsForSteps = array(
		self::STEP_FACE      => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT1 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT2 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT3 => 'https://www.google.ru/images/srpr/logo11w.png',
		self::STEP_DOCUMENT4 => 'https://www.google.ru/images/srpr/logo11w.png',
	);

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
	 * @param $aRequest array()
	 *
	 * @return array
	 */
	public function responseToRequest($aRequest)
	{
		// по умолчанию выдаём сообщение об ошибке
		$aResponse = array(
			'code'    => IdentifyApiComponent::ERROR_REQUEST_HANDLING,
			'message' => IdentifyApiComponent::C_ERR_NOT_POST_REQUEST,
		);

		// получаем токен....
		$sToken = $aRequest['token'];

		// если это запрос без токена - значит, это запрос на авторизацию.
		if (empty($sToken)) {
			$sPhone = $aRequest['phone'];
			$sPassword = $aRequest['password'];

			// проверить, что содержит логин и пароль, иначе вернуть код -1;
			if (empty($sPhone) || empty($sPassword)) {
				return $aResponse;
			}

			$bAuth = $this->getClientAuth($sPhone, $sPassword);

			// если не удалось авторизоваться по логину-паролю вернуть код -1.
			if (!$bAuth) {
				return $aResponse;
			}

			// авторизация успешна; генерируем соответствующий токен todo: убрать заглушку.
			$iStepNumber = self::STEP_FACE;
			$sToken = $this->generateToken(self::TMP_HASH, $iStepNumber);

			// ответ: ошибки нет, всё ок, посылаем дальнейшую инструкцию.
			$aResponse = array(
				'code'   => IdentifyApiComponent::ERROR_NONE_WITH_INSTRUCTION,
				'result' => array(
					'token'       => $sToken,
					'instruction' => IdentifyApiComponent::$aInstructionsForSteps[$iStepNumber],
				),
			);
		} else {
			// есть токен - берём из него информацию
			$aData = $this->decryptToken($sToken);
			$sUserHash = !empty($aData['0']) ? $aData['0'] : false;
			$iStepNumber = !empty($aData['1']) ? $aData['1'] : false;

			// ошибка в данных из токена
			if ($sUserHash === false || $iStepNumber === false) {
				return $aResponse;
			}

			// если хэш неверный - ошибка. todo: убрать заглушку
			if ($sUserHash !== self::TMP_HASH) {
				return $aResponse;
			}

			$sImageBase64 = !empty($aRequest['image']) ? $aRequest['image'] : false;

			// нет картинки в ПОСТ-запросе или это не картинка
			if ($sImageBase64 === false || !$this->getIsImage($sImageBase64)) {
				return $aResponse;
			}

			// если не получилось сохранить изображение - ошибка
			if (!$this->saveImage($sUserHash, $sImageBase64, $iStepNumber)) {
				return $aResponse;
			}

			// получаем ответ исходя из номера шага.
			$aResponse = $this->getResponseByStep($iStepNumber);

			// если ошибки не было - добавляем в ответ токен со следующим номером шага.
			if ($aResponse['code'] !== self::ERROR_REQUEST_HANDLING) {
				$aResponse['result']['token'] = $this->generateToken($sUserHash, $iStepNumber + 1);
			}
		}

		return $aResponse;
	}

	/**
	 * Авторизация в API по логину (номер телефона) и паролю
	 *
	 * @param $sPhone
	 * @param $sPassword
	 *
	 * @return bool
	 */
	public function getClientAuth($sPhone, $sPassword)
	{
		// тестовые данные todo: убрать заглушку
		return ($sPhone === "9513570000" && $sPassword === "Aa12345");
	}

	/**
	 * Получаем ответ исходя из номера шага.
	 *
	 * @param $iStepNumber номер шага
	 *
	 * @return array
	 */
	private function getResponseByStep($iStepNumber)
	{
		// по умолчанию выдаём сообщение об ошибке
		$aResponse = array(
			'code'    => IdentifyApiComponent::ERROR_REQUEST_HANDLING,
			'message' => IdentifyApiComponent::C_ERR_NOT_POST_REQUEST,
		);

		switch ($iStepNumber) {
			case self::STEP_FACE:
			case self::STEP_DOCUMENT1:
			case self::STEP_DOCUMENT2:
			case self::STEP_DOCUMENT3:
			case self::STEP_DOCUMENT4:
				$aResponse = array(
					'code'   => IdentifyApiComponent::ERROR_NONE_WITH_INSTRUCTION,
					'result' => array(
						'document'    => $iStepNumber,
						'title'       => self::$aTitlesForSteps[$iStepNumber],
						'instruction' => self::$aInstructionsForSteps[$iStepNumber],
						'example'     => self::$aExamplesForSteps[$iStepNumber],
						'description' => self::$aDescriptionsForSteps[$iStepNumber],
					)
				);
				break;

			case self::STEP_DOCUMENT5:
				$aResponse = array(
					'code'   => IdentifyApiComponent::ERROR_NONE_WITH_INSTRUCTION,
					'result' => array(
						'done'        => 1,
						'instruction' => self::$aInstructionsForSteps[$iStepNumber],
					)
				);
				break;
		}


		return $aResponse;
	}

	/**
	 * @param $sImageBase64
	 *
	 * @return bool
	 */
	private function getIsImage($sImageBase64)
	{
		$sImage = base64_decode($sImageBase64);

		// todo: fix

		return (imagecreatefromstring($sImage) !== false);
	}

	/**
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
