<?php

mb_internal_encoding("UTF-8");

$a = array(

	'basePath'	=> dirname(__FILE__). '/..',
	'name'		=> 'Kreddy',
	'language'	=> 'ru',

	'timeZone'	=> 'Europe/Moscow',

	'import' => array(

		'application.models.*',
		'application.models.forms.*',

		'application.components.*',
		'application.components.mailer.*',
		'application.components.crypt.*',

		'application.components.client.*',
		'application.components.client.handlers.*',

		'application.components.events.*',
		'application.components.events.observer.*',
		'application.components.events.client.*',
		'application.components.events.clientProduct.*',
		'application.components.events.clientState.*',
		'application.components.events.loan.*',
		'application.components.events.payment.*',
		'application.components.events.admin.*',

		'application.extensions.*',
        'application.extensions.payments.PaymentsInfo',
        'application.extensions.sms.*',

		'application.commands.*',
	),

	'modules' => array(

	),

	'components' => array(

		'clientRequest' => array(
			'class' => 'application.components.client.ClientRequestComponent',
		),

		'exportBuffer' => array(
			'class' => 'application.components.export.ExportBuffer',
		),

        'transferTypeFactory' => array(
            'class' => 'application.components.payment.TransferTypeFactoryComponent',
        ),

        'transferManager' => array(
            'class' => 'application.components.payment.TransferManagerComponent',
        ),

        'invoiceTypeFactory' => array(
            'class' => 'application.components.payment.InvoiceTypeFactoryComponent',
        ),

        'invoiceManager' => array(
            'class' => 'application.components.payment.InvoiceManagerComponent',
        ),

        'invoicePackage' => array(
            'class' => 'application.components.payment.InvoicePackageComponent',
        ),

        'kreddyCard' => array(
            'class' => 'application.components.card.KreddyCardComponent',
        ),

        'clientCard' => array(
            'class' => 'application.components.client.ClientCardComponent',
        ),

		'clientState' => array(
			'class' => 'application.components.client.ClientStateComponent',
		),

		'observer' => array(
			'class' => 'application.components.events.observer.ObserverComponent',
		),

		'document' => array(
			'class' => 'application.components.documents.DocumentComponent',
			'sDocumentDir' => '/docs/',
		),

		'format' => array(
			'class' => 'application.components.Formatter',
		),

		'request' => array(
			'enableCookieValidation' => true,
		),

		'bootstrap' => array(
			'class' => 'ext.bootstrap.components.Bootstrap',
		),

		'clientScript' => array(
			'class' => 'ClientScript'
		),

		'reqLog' => array(
			'class' => 'application.components.RequestLogComponent'
		),

		'session' => array(
			'sessionName'		=> 'iks',
			'cookieMode'		=> 'only',
			'timeout'			=> 60*60*8,
			'gCProbability'		=> 0,
			'cookieParams'		=> array(
				'path'		=> '/',
				'domain'	=> 'kreddy.ru',
			),
		),

		'site' => array(
			'class' => 'SiteParams',
			'params' => array(
				'hostname'		=> 'admin.kreddy.ru',
				'link'			=> 'https://admin.kreddy.ru:8081',
				'link_admin'	=> 'https://admin.kreddy.ru:8081/admin'
			),
		),

		'urlManager' => array(
			'urlFormat'	=> 'path',
			'urlSuffix'	=> '/',
			'rules'	=> array(

			),
			'showScriptName'=>false
		),

		'cache' => array(
			'class' =>  'CDummyCache',
		),

		'log' => array(
			'class'		=> 'CLogRouter',
			'routes'	=> array( ),
		),

	),

	'params' => array(
		'authCookieName' => 'iksc',
	),
);

$a = CMap::mergeArray( $a, require( dirname(__FILE__) . '/custom/main.php' ) );

return $a;
