Qiwi Shop Emulator
=========

Gateway emulates shop for the qiwi system (REST Protocol).

# Requirements

- php >=5.3.0
- Laravel Framework 4.1.*
- MySQL Database
- Laravel queue driver configuration
- User auth identifier in your web project

# Uses

- bootstrap cdn
- jquery cdn

# Installation

## Composer

Package only:

    {
        "require": {
            "fintech-fab/qiwi-shop": "dev-master"
        },
    }

Package dependency:

    {
        "require": {
	        "php": ">=5.3.0",
	        "laravel/framework": "4.1.*",
            "fintech-fab/qiwi-shop": "dev-master"
        },
	    "require-dev": {
		    "phpunit/phpunit": "3.7.*",
		    "mockery/mockery": "dev-master"
	    },
    }

Run it:

	composer update
	php artisan dump-autoload

## Local configuration

Add service provider to `config/app.php`:

	'providers' => array(
		'FintechFab\QiwiShop\QiwiShopServiceProvider'
	)

### Database connection named 'ff-qiwi-shop'

Add to `config/#env#/database.php`:

```PHP
'connections' => array(
	'ff-qiwi-shop' => array(
		'driver'    => 'mysql',
		'host'      => 'your-mysql-host',
		'database'  => 'your-mysql-database',
		'username'  => 'root',
		'password'  => 'your-mysql-password',
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => 'your-table-prefix',
	),

),
```

## Migrations

	php artisan migrate --package="fintech-fab/qiwi-shop" --database="ff-qiwi-shop"

### Custom user auth identifier:

Default, user auth id detect by `Auth::user()->getAuthIdentifier()`.
Your can set integer value (e.g. `'user_id' => 1`), or use some your function with identifier return;

For this, publish configuration from package:

	php artisan config:publish fintech-fab/qiwi-shop

And change user auth identifier for your web project `app/config/packages/fintech-fab/qiwi-shop/config.php`:

	'user_id' => 'user-auth-identifier',

## Development How to

### Workbench migrations

	php artisan migrate:reset --database="ff-qiwi-shop"
	php artisan migrate --bench="fintech-fab/qiwi-shop" --database="ff-qiwi-shop"

	php artisan migrate:reset --database="ff-qiwi-shop" --env="testing"
	php artisan migrate --bench="fintech-fab/qiwi-shop" --database="ff-qiwi-shop" --env="testing"

### Package migrations

	php artisan migrate:reset --database="ff-qiwi-shop"
	php artisan migrate --package="fintech-fab/qiwi-shop" --database="qiwi-shop"

	php artisan migrate:reset --database="ff-qiwi-shop" --env="testing"
	php artisan migrate --package="fintech-fab/qiwi-shop" --database="ff-qiwi-shop" --env="testing"

### Workbench publish

	php artisan config:publish --path=workbench/fintech-fab/qiwi-shop/src/config fintech-fab/qiwi-shop


