<?php namespace Bitbeans\Lencryption;

use Illuminate\Support\Facades\Facade;

class LencryptionFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'lencryption'; }
}