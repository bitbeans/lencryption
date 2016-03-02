<?php namespace Bitbeans\Lencryption;

use Illuminate\Support\ServiceProvider;

class LencryptionServiceProvider extends ServiceProvider {

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
	    $this->publishes([
	        __DIR__.'/config/lencryption.php' => config_path('lencryption.php'),

	    ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['lencryption'] = $this->app->share(function($app)
		{
			return new Lencryption;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('lencryption');
	}

}