<?php namespace FintechFab\ActionsCalc;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

/**
 * Class ActionsCalcServiceProvider
 *
 * @package FintechFab\ActionsCalc
 */
class ActionsCalcServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		Config::set('database.default', 'ff-actions-calc');
		$this->package('fintech-fab/actions-calc', 'ff-actions-calc');
		include __DIR__ . '/../../routes.php';
		include __DIR__ . '/../../filters.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
