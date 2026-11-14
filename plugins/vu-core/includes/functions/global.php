<?php
/**
 * Expose Global Functions from plugin functionality.
 *
 * @package VUCore
 */

use League\Container\Container;
use League\Container\Definition\DefinitionAggregate;
use League\Container\Inflector\InflectorAggregate;
use League\Container\ServiceProvider\ServiceProviderAggregate;
use League\Container\ReflectionContainer;

/**
 * Global class container.
 *
 * @return Container
 */
function cc(): Container {
	static $app = null;

	if ( ! $app ) {
		$app = new Container(
			new DefinitionAggregate(),
			new ServiceProviderAggregate(),
			new InflectorAggregate()
		);
		$app->delegate( new ReflectionContainer( true ) );
	}

	return $app;
}
