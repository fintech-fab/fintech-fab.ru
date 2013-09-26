<?php
$I = new WebGuy($scenario);
$I->wantTo('Test account login');
$I->amOnPage('/account/login');
$I->see('Вход в личный кабинет');
$I->see('Номер телефона');
$I->fillField('#AccountLoginForm_username','9173330247');
$I->fillField('#AccountLoginForm_password','1234');
$I->click('#login-form button[type="submit"]');
$I->see('Ваш Пакет займов');
$I->seeLink('Выход');
$I->click('Выход');
$I->see('Сколько взял - столько вернул!');