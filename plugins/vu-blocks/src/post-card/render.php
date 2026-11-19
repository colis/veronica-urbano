<?php
/**
 * VU Blocks
 * Card Block - Render
 *
 * @package VUBlocks
 *
 * $attributes provides the post ID for backend rendering.
 * $block provides the post ID for frontend rendering.
 */

$scope = vu_blocks_block_render_scope( $attributes ?? null, $block ?? null, $content ?? null );
if ( null === $scope ) {
	return;
}

[ $attributes, $block, $content ] = $scope;

if ( empty( $attributes['postId'] ) && empty( $block->context['postId'] ) ) {
	return;
}

$attrs = [
	'post' => [
		'id' => ! empty( $attributes['postId'] ) ? $attributes['postId'] : $block->context['postId'],
	],
];

// Render the card template.
get_template_part( 'template-parts/cards/card', 'project-listing', $attrs );
