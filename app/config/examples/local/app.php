<?php
return array(

	'url'       => 'http://fintech-fab.dev',

	'vanguard'  => array(
		'recipient_order_form' => 'post@example.com',
	),

	/**
	 * при первом запуске:
	 * 1. Закомментировать пакеты, которые живут в workbench
	 * 2. Запустить composer update
	 * 3. Запустить php artisan dump-autoload
	 * 4. Раскомментировать нужные пакеты для их работы
	 */
	'providers' => array(
		//'FintechFab\QiwiGate\QiwiGateServiceProvider',
		//'FintechFab\QiwiShop\QiwiShopServiceProvider',
	),

);