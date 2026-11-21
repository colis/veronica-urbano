<?php
/**
 * Shared polaroid viewer, managed through the WordPress Interactivity API.
 *
 * @package VUTheme
 */

use VUBlocks\Blocks\Extensions\Polaroid;

defined( 'ABSPATH' ) || die();

wp_interactivity_state(
	Polaroid::STORE,
	[
		'ready'      => false,
		'polaroidId' => 0,
		'title'      => '',
		'isSelected' => false,
		'images'     => array_values( $args['images'] ?? [] ),
	]
);

?>

<div
	data-wp-interactive="<?php echo esc_attr( Polaroid::STORE ); ?>"
	data-wp-init="callbacks.init"
	data-wp-router-region='{"id":"polaroid","attachTo":"body"}'
>
	<div
		class="c-polaroid"
		id="<?php echo esc_attr( Polaroid::DIALOG_ID ); ?>"
		aria-label="<?php esc_attr_e( 'Polaroid', 'vu' ); ?>"
		data-wp-bind--aria-label="state.title"
		aria-hidden="true"
	>
		<div class="c-polaroid__overlay" data-wp-on--click="actions.close"></div>

		<div class="c-polaroid__content" role="document">
			<button
				type="button"
				class="c-polaroid__close"
				aria-label="<?php esc_attr_e( 'Close polaroid', 'vu' ); ?>"
				data-wp-on--click="actions.close"
				autofocus
			></button>

			<template
				data-wp-each--polaroid="state.images"
				data-wp-each-key="context.polaroid.id"
			>
				<figure
					data-wp-bind--hidden="!state.isSelected"
					hidden
				>
					<img
						data-wp-bind--src="context.polaroid.src"
						data-wp-bind--alt="context.polaroid.alt"
						data-wp-bind--width="context.polaroid.width"
						data-wp-bind--height="context.polaroid.height"
						data-wp-bind--srcset="context.polaroid.srcset"
						data-wp-bind--sizes="context.polaroid.sizes"
						loading="lazy"
						decoding="async"
					>

					<figcaption
						data-wp-text="context.polaroid.caption"
						data-wp-bind--hidden="!context.polaroid.caption"
					></figcaption>
				</figure>
			</template>
		</div>
	</div>
</div>
