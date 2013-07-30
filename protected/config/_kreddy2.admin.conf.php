<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

$a = CMap::mergeArray(

	require(dirname(__FILE__) . '/main.php'),

	array(

		'name' => 'Kreddy Admin',
		'preload' => array('log', 'bootstrap'),
		'theme' => 'bootstrap',

		'import' => array(
			'application.models.*',
			'application.models.forms.*',
			'application.components.*',
			'application.components.mailer.*',
			'application.components.documents.*',
			'application.components.utils.*',
			'application.extensions.*',
			'application.modules.srbac.controllers.SBaseController.*',
			'application.modules.admin.models.*',
			'application.modules.admin.widgets.*',
			'application.modules.pay.models.*',
		),

		'modules' => array(

			'admin' => array(

				'import' => array(
					'application.modules.admin.controllers.*',
					'application.modules.admin.models.*',
					'application.modules.admin.models.forms.*',
				),

				'components' => array(),

				'modules' => array(),
			),

			'paymentEmulation' => array(),

			'srbac' => array(
				'class' => 'application.modules.srbac.SrbacModule',
				'userclass' => 'SiteAdminUser',
				'userid' => 'id',
				'username' => 'name',
				'delimeter' => '@',
				'debug' => true,
				'pageSize' => 100,
				'superUser' => 'Authority',
				'css' => 'srbac.css',
				'layout' => 'application.modules.srbac.views.layouts.srbac',
				'notAuthorizedView' => 'srbac.views.authitem.unauthorized',
				'alwaysAllowed' => array(),
				'userActions' => array(),
				'listBoxNumberOfLines' => 15,
				'imagesPath' => 'srbac.images',
				'imagesPack' => 'noia',
				'header' => 'srbac.views.authitem.header',
				'footer' => 'srbac.views.authitem.footer',
				'showHeader' => false,
				'showFooter' => false,
				'alwaysAllowedPath' => 'srbac.components',
				'import' => array(
					'application.modules.admin.models.*',
					'application.modules.admin.components.*',
				),
			),

			'email' => array(
				'delivery' => 'php',
			),

		),

		'components' => array(

			'request' => array(
				'class' => 'HttpRequest',
				'enableCsrfValidation' => true,
				'enableCookieValidation' => true,
				'aCsrfValidationRoutes' => array(
					'admin/main/login',//форма логина
				),
			),

			'client' => array(
				'class' => 'application.components.client.ClientComponent',
			),

			'clientProduct' => array(
				'class' => 'application.components.client.ClientProductComponent',
			),

			'sms' => array(
				'class' => 'application.components.SmsComponent',
				'bEnabled' => false,
			),

			'email' => array(
				'class' => 'application.components.EmailComponent',
				'sFrom' => 'debug@kreddy.ru',
			),

			'excel' => array(
				'class' => 'application.extensions.PHPExcel.PHPExcel',
				'import' => array(
					'application.extensions.excel.lib.*',
				),
			),

			'bootstrap' => array(
				'class' => 'bootstrap.components.Bootstrap',
			),

			'session' => array(
				'cookieParams' => array(
					'domain' => 'admin.kreddy.ru',
				),
			),

			'user' => array(
				'class' => 'application.modules.admin.components.SiteAdminUserComponent',
				'loginUrl' => '/login',
				'allowAutoLogin' => true
			),

			'site' => array(
				'class' => 'SiteParams',
				'params' => array(
					'hostname' => 'admin.kreddy.ru',
					'link' => 'http://admin.kreddy.ru/admin',
				),
			),

			'authManager' => array(
				'class' => 'application.modules.srbac.components.SDbAuthManager',
				'connectionID' => 'db',
				'itemTable' => 'site_admin_auth_item',
				'itemChildTable' => 'site_admin_auth_item_child',
				'assignmentTable' => 'site_admin_auth_assignment',
			),

			'urlManager' => array(
				'rules' => array(
					'' => 'admin/main/index',
					'/login' => 'admin/main/login',
					'/logout' => 'admin/main/logout',
					'/admin/client/tabs/<action>*' => 'admin/client/<action>',
					// маршрутизация для эмулятора внешних платежных систем
					'/api/v2/prv/<intProvId>/bills/<intInvoiceId>*' => 'paymentEmulation/qiwi/billInvoice',
					'/api/v2/prv/<intProvId>/bills/<intInvoiceId>/refund/<intRefundId>' => 'paymentEmulation/qiwi/refundInvoice',
					'/qiwi/sendnotify' => 'paymentEmulation/qiwi/sendNotify',
					'/qiwi-notify.php' => 'paymentEmulation/qiwi/notified', // для теста получения уведомленияуведомления
					'/newsymbol*' => 'paymentEmulation/newsymbol/createRequest', // для теста получения уведомления
					'/newsymbolMobile*' => 'paymentEmulation/newsymbol/createRequest', // для теста получения уведомления
					'/api/bankmoscow' => 'paymentEmulation/BankMoscow/index', //эмулятор API Банка Москвы
					'/inplat*' => 'paymentEmulation/inplat/index',
					// конец правил маршрутизации для эмулятора
				),
				'showScriptName' => false
			),

			'mailer' => array(
				'class' => 'MailerComponent',
				'unsubscribe_email' => 'unsubscribe@kreddy.ru',
				'email_to_test' => 'test@test.local',
				'email_to_fake' => 'test@test.local',
				'params' => array(
					'hostname' => 'kreddy.ru',
					'fromName' => 'Kreddy Informer',
					'fromEmail' => 'info@kreddy.ru',
					'noreplyName' => 'kreddy.ru',
					'noreplyEmail' => 'noreply@kreddy.ru',
					'adminEmail' => 'it@kreddy.ru',
					'subject' => 'сообщение от сайта {hostname}',
				),
			),

			'errorHandler' => array(
				'errorAction' => 'admin/main/error',
			),

			'log' => array(
				'class' => 'CLogRouter',
				'routes' => array(
					array(
						'class' => 'CFileLogRoute',
						'levels' => 'error, warning',
						'categories' => 'system.*, app',
					),
					array(
						'class' => 'DBLogger',
						'levels' => 'app, warning, error',
						'logTableName' => 'system',
						'connectionID' => 'db_log',
						'autoCreateLogTable' => false,
						'filter' => 'CLogFilter',
					),
				),
			),
		),
	)
);

// добавим компоненты для базы и модуля платежей
$a['components'] = CMap::mergeArray( $a['components'], require( dirname(__FILE__) . '/inc/db.php' ));
$a['components'] = CMap::mergeArray( $a['components'], require( dirname(__FILE__) . '/inc/payments.php' ));

$a = CMap::mergeArray($a, require(dirname(__FILE__) . '/custom/admin.php'));

return $a;
