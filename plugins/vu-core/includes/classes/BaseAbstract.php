<?php
/**
 * VU Core Base
 *
 * @package VUCore
 */

namespace VUCore;

use League\Container\Container;

/**
 * BaseAbstract Abstract Class.
 *
 * @package VUCore
 */
abstract class BaseAbstract {

	/**
	 * App container object.
	 *
	 * @var Container $app
	 */
	protected Container $app;

	/**
	 * Either inject an already constructed container or create a new on when object is instantiated
	 */
	public function __construct() {
		$this->app = cc();
	}
}
