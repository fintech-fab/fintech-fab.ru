<?php
/*
 * Компонент ClientForm
 * занимается обработкой данных сессии и cookies
 * и передачей результата по запросу контроллера форм.
 * Также выполняет команды контроллера по обработке форм.
 *
 * Соответствие шага в сессии обрабатываемой форме и отображаемому представлению
 * Шаг - Модель (отображение)     - Представление
 * 0 - ClientSelectProductForm  - clientselectproduct
 * 1 - ClientGetWayForm         - clientgetway
 * 2 - ClientPersonalDataForm   - clientpersonaldata
 * 3 - ClientAddressForm        - clientaddress
 * 4 - ClientJobInfoForm        - clientjobinfo
 * 5 - ClientSendForm           - clientsend
 * 6 - ______________           - /pages/view/formsent
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

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * Если да, выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function ajaxValidation()
	{
		return false;
	}

	public function getFormModel()
	{
		switch($this->current_step)
		{
			case 0:
				return new ClientSelectProductForm();
				break;
			case 1:
				return new ClientSelectGetWayForm();
				break;
			case 2:
				return new ClientPersonalDataForm();
				break;
			case 3:
				return new ClientAddressForm();
				break;
			case 4:
				return new ClientJobInfoForm();
				break;
			case 5:
				return new ClientSendForm();
				break;
			default:
				return new ClientSelectProductForm();
				break;
		}
	}

	/**
	 * Возвращает название необходимого для генерации представления.
	 *
	 * @return string
	 */
	public function getView()
	{
		switch($this->current_step)
		{
			case 0:
				return 'clientselectproduct';
				break;
			case 1:
				return 'clientselectgetway';
				break;
			case 2:
				return 'clientpersonaldata';
				break;
			case 3:
				return 'clientaddress';
				break;
			case 4:
				return 'clientjobinfo';
				break;
			case 5:
				return 'clientsend';
				break;
			default:
				return 'clientselectproduct';
				break;
		}
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|bool
	 */

	public function getPostData()
	{
		switch($this->current_step)
		{
			case 0:
			{
				if(isset($_POST['ClientSelectProductForm']))
				{
					return $_POST['ClientSelectProductForm'];
				}
				return false;
			}
				break;
			case 1:
			{
				if(isset($_POST['ClientSelectGetWayForm']))
				{
					return $_POST['ClientSelectGetWayForm'];
				}
				return false;
			}
				break;
			case 2:
			{
				if(isset($_POST['ClientPersonalDataForm']))
				{
					return $_POST['ClientPersonalDataForm'];
				}
				return false;
			}
				break;
			case 3:
			{
				if(isset($_POST['ClientAddressForm']))
				{
					return $_POST['ClientAddressForm'];
				}
				return false;
			}
				break;
			case 4:
			{
				if(isset($_POST['ClientJobInfoForm']))
				{
					return $_POST['ClientJobInfoForm'];
				}
				return false;
				break;
			}
			case 5:
			{
				if(isset($_POST['ClientSendForm']))
				{
					return $_POST['ClientSendForm'];
				}
				return false;
			}
				break;
			default:
				return false;
				break;

		}
	}

	/*
	 * Переводит обработку форм на следующий шаг
	 *
	 */
	public function nextStep()
	{

		$this->current_step++;
		Yii::app()->session['current_step']=$this->current_step;

	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract $model
	 */
	public function formDataProcess($model)
	{

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
