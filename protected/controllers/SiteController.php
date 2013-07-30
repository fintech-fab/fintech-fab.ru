<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	/*public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}*/

    public function actionJoin()
    {
        $model=new ClientJoinForm;
        // uncomment the following code to enable ajax-based validation
        if(isset($_POST['ajax']) && $_POST['ajax']==='client-join')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }


        if(isset($_POST['ClientJoinForm']))
        {
            $model->attributes=$_POST['ClientJoinForm'];
            if($model->validate())
            {
               // form inputs are valid, do something here
				Yii::app()->session['client_id'] = '';
                $client=new ClientData;
                if(!$client->checkClientByPhone($model->phone))
                {
					//array('client_id'=>'','phone'=>'')
					if(($this->compareDataInCookie('client','phone',$model->phone))&&($cookieData = $this->getDataFromCookie('client')))
					{
						Yii::app()->session['client_id'] = $cookieData['client_id'];
					}
					else
					{
						$client=$client->addClient($model);
						Yii::app()->session['client_id'] = $client->client_id;

						$data = array('client_id'=>$client->client_id,'phone'=>$client->phone);
						$this->saveDataToCookie('client',$data);
					}
                }
                else
                {
                	//уже наш клиент, радуем его этим фактом и посылаем на главную страницу
					$this->redirect(Yii::app()->createUrl("pages/view",array('name'=>'alreadyclient')));
                }
				//echo $cookieData['client_id'];
				//echo $cookieData['phone'];
                $this->redirect(Yii::app()->createUrl("site/form1"));
                return;
            }
        }
            $this->render('join',array('model'=>$model));
    }

    public function actionForm1()
    {
        $model=new ClientForm1;

		$client=new ClientData();
		$client_id=Yii::app()->session['client_id'];
		if($client_id=='') $this->redirect(Yii::app()->createUrl("site/index"));

        // uncomment the following code to enable ajax-based validation

        if(isset($_POST['ajax']) && $_POST['ajax']==='client-form1')
        {
			Yii::app()->session['form1_complete']=false;  //снимаем флаг готовности формы 1 перед валидацией
            echo CActiveForm::validate($model);
			$modelData = $model->getAttributes();
			$client->saveClientDataById($modelData,$client_id);
			$modelData += array('client_id' => $client_id);
			$this->saveDataToCookie('form1',$modelData);
            Yii::app()->end();
        }

        if(isset($_POST['ClientForm1']))
        {
            $model->attributes=$_POST['ClientForm1'];

			Yii::app()->session['form1_complete']=false; //снимаем флаг готовности формы 1 перед валидацией

            if($model->validate())
            {
            // form inputs are valid, do something here
				if(!$client->saveClientDataById($model->getAttributes(),$client_id))
				{
					$this->redirect(Yii::app()->createUrl("site/join"));
				}

				Yii::app()->session['form1_complete']=true; //при успешной валидации и сохранении данных ставим флаг готовности формы 1
                $this->redirect(Yii::app()->createUrl("site/form2"));
                return;
            }
        }

		if($this->compareDataInCookie('client','client_id',$client_id))
		{
			if($this->compareDataInCookie('form1','client_id',$client_id)&&($cookieData = $this->getDataFromCookie('form1')))
			{
				$model->setAttributes($cookieData);
			}
		}
		else
		{
			$this->redirect(Yii::app()->createUrl("site/index"));
		}

		//$model->setAttributes($client->getClientDataById($client_id));
        $this->render('form1',array('model'=>$model));
    }

    public function actionForm2()
    {

		if(!Yii::app()->session['form1_complete']) //проверяем заполнена ли форма 1 по флагу готовности, и если нет - возвращаемся к ней
		{
			$this->redirect(Yii::app()->createUrl("site/form1"));
		}

        $model=new ClientForm2;

		$client_id=Yii::app()->session['client_id'];
		$client=new ClientData();

        // uncomment the following code to enable ajax-based validation

        if(isset($_POST['ajax']) && $_POST['ajax']==='client-form2')
        {
            echo CActiveForm::validate($model);
			$modelData = $model->getAttributes();
			$modelData['complete']=0;
			$client->saveClientDataById($modelData,$client_id);
			$modelData += array('client_id' => $client_id);
			$this->saveDataToCookie('form2',$modelData);
            Yii::app()->end();
        }


        if(isset($_POST['ClientForm2']))
        {
            $model->attributes=$_POST['ClientForm2'];
            if($model->validate())
            {
                // form inputs are valid, do something here
				if(!$client->saveClientDataById($model->getAttributes(),$client_id))
				{
					$this->redirect(Yii::app()->createUrl("site/join"));
				}

	            Yii::app()->session['form2_complete']=true;
				$this->redirect(Yii::app()->createUrl("pages/view",array('name'=>'formsent')));
                return;
            }
        }

		if($this->compareDataInCookie('client','client_id',$client_id))
		{
			if($this->compareDataInCookie('form2','client_id',$client_id)&&($cookieData = $this->getDataFromCookie('form2')))
			{
				$model->setAttributes($cookieData);
			}
		}
		else
		{
			$this->redirect(Yii::app()->createUrl("site/index"));
		}

		//$model->setAttributes($client->getClientDataById($client_id));
        $this->render('form2',array('model'=>$model));
    }

	/**
	 * Идентификация личности  по видео
	 */
	public function actionIdentification() {

//		Yii::app()->session['form2_complete'] = true;
		if(!Yii::app()->session['form2_complete'])
		{
			$this->redirect(Yii::app()->createUrl("site/form2"));
		}

		$this->render('identification');
	}

	/**
	 * Загрузка документов
	 */
	public function actionDocuments() {
		$this->render('documents');
	}

    /**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
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