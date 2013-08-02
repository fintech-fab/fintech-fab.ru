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
	private $done_steps;

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

		if(!$this->done_steps = Yii::app()->session['done_steps'])
		{
			Yii::app()->session['done_steps']=0;
			$this->done_steps=0;
		}
	}

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * НЕ выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function ajaxValidation()
	{
		if(isset($_POST['ajax']) && $_POST['ajax']===get_class($this->getFormModel()))
		{
			return true;
		}
		return false;
	}

	/**
	 * Функция занимается сохранением данных,
	 * полученных при ajax-валидации,
	 * в сессию, куки и БД
	 *
	 * @param ClientData $clientData
	 * @param ClientCreateFormAbstract $oForm
	 */
	public function saveAjaxData($clientData, $oForm)
	{
		/**
		 * @var ClientData $client
		 */
		if(get_class($oForm)==='ClientPersonalDataForm')
		{
			if($oForm->phone)
			{
				/* проверяем, есть ли в куках информация о клиенте
				 * и сравниваем введенный телефон с телефоном в куках.
				 * в случае успешности восстанавливаем client_id из куки.
				 * иначе создаем нового клиента и сохраняем информацию
				 * о нем в сессию и куку.
				 */
				if(($cookieData = Cookie::getDataFromCookie('client'))&&(Cookie::compareDataInCookie('client','phone',$oForm->phone)))
				{
					Yii::app()->session['client_id'] = $cookieData['client_id'];
					$this->client_id = Yii::app()->session['client_id'];
				}
				else
				{
					/**
					 * функция addClient()ищет клиента в базе по телефону,
					 * и если находит - возвращает запись с указанным телефоном как результат
					 */
					$client=$clientData->addClient($oForm);
					Yii::app()->session['client_id'] = $client->client_id;

					$this->client_id=$client->client_id;

					$data = array('client_id'=>$client->client_id,'phone'=>$client->phone);
					Cookie::saveDataToCookie('client',$data);
				}
			}
			if($this->client_id)
			{

				$formData=$oForm->getAttributes();

				$formData['product']=Yii::app()->session['ClientSelectProductForm']['product'];
				$formData['get_way']=Yii::app()->session['ClientSelectGetWayForm']['get_way'];
				$clientData->saveClientDataById($formData,$this->client_id);

				//$this->saveDataToCookie(get_class($oForm),$formData);
			}
		}
		else
		{
			if($this->client_id)
			{
				$formData=$oForm->getAttributes();

				$clientData->saveClientDataById($formData,$this->client_id);

				$formData['client_id']=$this->client_id;
				//$this->saveDataToCookie(get_class($oForm),$formData);
			}
		}
		$formData=$oForm->getAttributes();
		Yii::app()->session[get_class($oForm)]=$formData;
		return;
	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract $model
	 */
	public function formDataProcess($clientData, $oForm)
	{
		/**
		 * @var ClientData $client
		 * @var ClientData $clientData
		 * @var ClientCreateFormAbstract $oForm
		 */
		if(get_class($oForm)==='ClientSelectProductForm')
		{
			Yii::app()->session['product']=$oForm->product;
		}
		elseif(get_class($oForm)==='ClientSelectGetWayForm')
		{
			Yii::app()->session['get_way']=$oForm->get_way;
		}
		if(get_class($oForm)==='ClientPersonalDataForm')
		{
			/* проверяем, есть ли в куках информация о клиенте
			 * и сравниваем введенный телефон с телефоном в куках.
			 * в случае успешности восстанавливаем client_id из куки.
			 * иначе создаем нового клиента и сохраняем информацию
			 * о нем в сессию и куку.
			 */
			if(($cookieData = Cookie::getDataFromCookie('client'))&&(Cookie::compareDataInCookie('client','phone',$oForm->phone)))
			{
				Yii::app()->session['client_id'] = $cookieData['client_id'];
				$this->client_id=Yii::app()->session['client_id'];
			}
			else
			{
				/**
				 * функция addClient()ищет клиента в базе по телефону,
				 * и если находит - возвращает запись с указанным телефоном как результат
				 */
				$client=$clientData->addClient($oForm);
				Yii::app()->session['client_id'] = $client->client_id;

				$this->client_id=$client->client_id;

				$data = array('client_id'=>$client->client_id,'phone'=>$client->phone);
				Cookie::saveDataToCookie('client',$data);
			}
			if($this->client_id)
			{
				$formData=$oForm->getAttributes();
				$formData['product']=Yii::app()->session['ClientSelectProductForm']['product'];
				$formData['get_way']=Yii::app()->session['ClientSelectGetWayForm']['get_way'];
				$clientData->saveClientDataById($formData,$this->client_id);


				//Cookie::saveDataToCookie(get_class($oForm),$formData);
			}
		}
		else
		{
			if($this->client_id)
			{
				$formData=$oForm->getAttributes();
				//var_dump($oForm->);
				//var_dump($formData);
				$clientData->saveClientDataById($formData,$this->client_id);
				//$formData['client_id']=$this->client_id;
				//Cookie::saveDataToCookie(get_class($oForm),$formData);
			}
		}
		$formData=$oForm->getAttributes();
		Yii::app()->session[get_class($oForm)]=$formData;
		return;
	}

	/**
	 * Возвращает номер текущего шага (нумерация с нуля)
	 *
	 * @return int
	 */
	public function getCurrentStep()
	{
		$this->current_step=Yii::app()->session['current_step'];

		return $this->current_step;
	}

	/**
	 * Возвращает число пройденных шагов (нумерация с нуля)
	 *
	 * @return int
	 */
	public function getDoneSteps()
	{
		$this->done_steps=Yii::app()->session['done_steps'];

		return $this->done_steps;
	}

	public function getFormModel() //возвращает модель, соответствующую текущему шагу заполнения формы
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
			case 6:
				return new InviteToIdentification();
				break;
			case 7:
			case 8:
			case 9:
				return false;
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
			case 6:
				return 'invitetoidentification';
				break;
			case 7:
				return 'identification';
				break;
			case 8:
				return 'documents';
				break;
			case 9:
				return false;
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
			case 6:
			{
				if(isset($_POST['InviteToIdentification']))
				{
					return $_POST['InviteToIdentification'];
				}
				return false;
				break;
			}
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
		if($this->done_steps<Yii::app()->session['current_step'])
		{
			Yii::app()->session['done_steps']=$this->done_steps=Yii::app()->session['current_step'];
		}

	}
}
