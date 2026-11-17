<?php
/**
 * Render repeated content for the CSS ticker without changing its editable list.
 *
 * @package VUBlocks
 */

use VUCore\Core\Icon;

$scope = vu_blocks_block_render_scope( $attributes ?? null, $block ?? null, $content ?? null );
if ( null === $scope ) {
	return;
}

[ $attributes, $block, $content ] = $scope;

$separator_icon     = $attributes['separatorIcon'] ?? '';
$separator_uri      = is_string( $separator_icon ) && '' !== $separator_icon ? Icon::get_data_uri( $separator_icon ) : '';
$wrapper_attributes = [];

if ( '' !== $separator_uri ) {
	$separator_size = (int) ( $attributes['separatorIconSize'] ?? 24 );
	$separator_size = $separator_size > 0 ? $separator_size : 24;

	$wrapper_attributes = [
		'class' => 'has-separator',
		'style' => sprintf(
			'--vu-ticker-separator-icon: url("%s"); --vu-ticker-separator-size: %dpx;',
			$separator_uri,
			$separator_size
		),
	];
}

$list_content = '';

foreach ( $block->inner_blocks as $inner_block ) {
	$list_content .= $inner_block->render();
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes( $wrapper_attributes ) ); ?>>
	<?php for ( $group = 0; $group < 2; $group++ ) : ?>
		<div class="wp-block-vu-ticker__group" <?php echo $group ? 'aria-hidden="true" inert' : ''; ?>>
			<?php echo wp_kses_post( $list_content ); ?>
		</div>
	<?php endfor; ?>
</div>
