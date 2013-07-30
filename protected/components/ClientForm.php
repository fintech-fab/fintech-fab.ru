<?php
/*
 * Компонент ClientForm
 * занимается обработкой данных сессии и cookies
 * и передачей результата по запросу контроллера форм.
 * Также выполняет команды контроллера по обработке форм.
 *
 * Соответствие шага в сессии обрабатываемой форме и отображаемому представлению
 * Шаг - Модель (обработка)    - Модель (отображение) - Представление
 * 0 - _____________________   - ClientSelectProduct  - clientselectproduct
 * 1 - ClientSelectProductForm - ClientGetWay         - clientgetway
 * 2 - ClientGetWayForm        - ClientPersonalData   - clientpersonaldata
 * 3 - ClientPersonalDataForm  - ClientAddressForm    - clientaddress
 * 4 - ClientAddressForm       - ClientJobInfoForm    - clientjobinfo
 * 5 - ClientJobInfoForm       - ClientSendForm       - clientsend
 * 6 - ClientSendForm          -                      - /pages/view/formsent
 */
class ClientForm
{
	private $client_id;
	private $current_step;

	/**
	 * Возвращает модель текущей формы.
	 *
	 * @return ClientCreateFormAbstract
	 */
	public function init()
	{
		if(!$this->client_id = Yii::app()->session['client_id'])
		{
			$this->client_id=false;
		}

		if(!$this->current_step = Yii::app()->session['current_step'])
		{
			Yii::app()->session['current_step']=0;
			$this->current_step=0;
		}
	}

	public function getFormModel()
	{
		switch($this->current_step)
		{
			case 0:
				return new ClientPersonalDataForm();
				break;
			default:
				return new ClientPersonalDataForm();
				break;

		}
	}

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * Если да, выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function ajaxValidation()
	{
		return;
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|null
	 */
	public function getPostData()
	{
		return;
	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract $model
	 */
	public function formDataProcess($model)
	{

	}

	/**
	 * Возвращает название необходимого для генерации представления.
	 *
	 * @return string
	 */
	public function getView()
	{
		return;
	}

	private function compareDataInCookie($cookieName,$attributeName,$checkValue)
	{
		$dataInCookie = false;
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку

			$aDecrypt= @unserialize($sDecrypt);
			if($aDecrypt&&($checkValue == $aDecrypt[$attributeName]))
			{
				$dataInCookie=true;
			}
		}
		return $dataInCookie;
	}

	private function getDataFromCookie($cookieName)
	{
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку
			$aDecrypt= @unserialize($sDecrypt);
			return $aDecrypt;
		}
		return false;
	}

	private function saveDataToCookie($cookieName,$data)
	{
		$sEncrypt = serialize($data);
		$cookieData = CryptArray::encryptVal($sEncrypt);

		$cookie = new CHttpCookie($cookieName, $cookieData);
		$cookie->expire = time()+60*60*2;
		Yii::app()->request->cookies[$cookieName] = $cookie;
	}
}
