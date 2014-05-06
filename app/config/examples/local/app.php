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
		//'FintechFab\QiwiGate\QiwiGateServiceProvider',
		//'FintechFab\QiwiShop\QiwiShopServiceProvider',
	],

];