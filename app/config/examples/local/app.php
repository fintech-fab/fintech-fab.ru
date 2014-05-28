<?php
return [

	'url'       => 'http://fintech-fab.dev',

	'vanguard'  => [
		'recipient_order_form' => 'post@example.com',
	],

	/**
	 * при первом запуске:
	 * 1. Закомментировать пакеты, которые живут в workbench
	 * 2. Запустить composer update
	 * 3. Запустить php artisan dump-autoload
	 * 4. Раскомментировать нужные пакеты для их работы
	 */
	'providers' => [
		//100500 => 'FintechFab\QiwiGate\QiwiGateServiceProvider',
		//100501 => 'FintechFab\QiwiShop\QiwiShopServiceProvider',
		//100502 => 'FintechFab\BankEmulator\BankEmulatorServiceProvider',
	],

	'logglykey' => '',
	'logglytag' => '',

];