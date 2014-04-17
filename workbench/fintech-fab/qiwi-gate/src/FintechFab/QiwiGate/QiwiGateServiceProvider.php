<?php namespace FintechFab\QiwiGate;

use Illuminate\Support\ServiceProvider;

class QiwiGateServiceProvider extends ServiceProvider
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
		$this->package('fintech-fab/qiwi-gate', 'ff-qiwi-gate');
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
