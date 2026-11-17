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

/**
 * Normalize variables for a block render.php template.
 *
 * @param array<string, mixed>|null $attributes           Block attributes from WP_Block.
 * @param WP_Block|null             $block                Block instance from WP_Block.
 * @param string|null               $content              Saved inner blocks markup.
 * @param array<string, mixed>|null $template_attributes  Content Selector card partial context (no WP_Block).
 *
 * @return array{0: array<string, mixed>, 1: WP_Block|null, 2: string}|null Tuple or null when not in a render context.
 */
function vu_blocks_block_render_scope(
	?array $attributes,
	?WP_Block $block,
	?string $content = null,
	?array $template_attributes = null
): ?array {
	if ( null !== $template_attributes ) {
		return [
			is_array( $attributes ) ? $attributes : [],
			$block instanceof WP_Block ? $block : null,
			$content ?? '',
		];
	}

	if ( ! $block instanceof WP_Block ) {
		return null;
	}

	return [
		null === $attributes ? $block->attributes : array_merge( $block->attributes, $attributes ),
		$block,
		$content ?? '',
	];
}
