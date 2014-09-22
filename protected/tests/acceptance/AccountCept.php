<?php
/**
 * @var $scenario
 */


$I = new WebGuy($scenario);
$I->wantTo('Test account login');
$I->amOnPage('/form');
$I->see('Выбери Пакет займов');
//$I->seeElement('input[name="product"]');
//$val = $I->grabValueFrom('input[name="ClientSelectProductForm[product]"]');
//$I->selectOption('input[name="ClientSelectProductForm[product]"]','Покупки');
//л$I->click('input [@id="#ClientSelectProductForm_product_2"');
$I->submitForm('#ClientSelectProductForm', array('ClientSelectProductForm' => array(
	'product' => '99999',
)));


/*$I->submitForm('#ClientSelectProductForm', array('ClientSelectProductForm' => array(
	'ClientSelectProductForm[product]' => '999',
)));*/
$I->see('Выбери Пакет займов');


/*$I->submitForm('#ClientSelectProductForm', array('ClientSelectProductForm' => array(
	'product' => '',
)));

$I->see('Необходимо заполнить поле');

$I->submitForm('#ClientSelectProductForm', array('ClientSelectProductForm' => array(
	'product' => '9',
)));
$I->see('Личные данные');
$I->see('Место работы');*/
/*
$I->amOnPage('/account/login');
$I->see('Вход в личный кабинет');
$I->see('Номер телефона');
$I->fillField('#AccountLoginForm_username','9173330247');
$I->fillField('#AccountLoginForm_password','1234');
$I->click('#login-form button[type="submit"]');
$I->see('Ваш Пакет займов');
$I->see('Нет активных Пакетов');
$I->see('Для получения подробной информации и');

$I->click('#sendSms');
$I->see('SMS с паролем успешно отправлено');
$I->see('Введите пароль из SMS:');
$I->fillField('#SMSPasswordForm_smsPassword','1111');
$I->click('#checkSmsPass button[type="submit"]');
$I->dontSee('Для получения подробной информации и');
$I->seeLink('Подключить Пакет');
$I->click('Подключить Пакет');




//$I->see('Кредди 3000');

//$I->seeElement('#products-form input[name="ClientSubscribeForm[product]"]');
//$I->seeElement('#ClientSubscribeForm_product_0');

//$I->click('#products-form label[for=ClientSubscribeForm_product_0]');
//TODO доделать
$I->click('#products-form button[type="submit"]');
$I->see(' Для подключения Пакета требуется подтверждение одноразовым SMS-кодом ');
$I->click('#products-form button[type="submit"]');
$I->see('Код подтверждения операции успешно отправлен');
$I->see('Код из SMS');
$I->fillField('#SMSCodeForm_smsCode','1111');
$I->click('#products-form button[type="submit"]');
$I->see('Ваша заявка принята. Ожидайте решения.');


$I->amOnPage('/account/history');
$I->see('История операций');
$I->see('Дата и время');
$I->amOnPage('/account');
$I->see('Заявка в обработке.');
$I->seeLink('Выход');
$I->click('Выход');
$I->see('Сколько взял - столько вернул!');
*/