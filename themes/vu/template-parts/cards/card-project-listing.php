<?php
/**
 * Story Card
 *
 * @package VUTheme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die();

$classnames = [
	'grid-card',
	'c-card',
	'c-card-project-listing',
];

$card_post    = $args['post'] ?? [];
$card_post_id = $card_post['id'] ?? get_the_ID();
$category_tax = 'category';

$card_image     = get_post_thumbnail_id( $card_post_id );
$card_title     = get_the_title( $card_post_id );
$card_link      = get_permalink( $card_post_id );
$card_term_list = get_the_terms( $card_post_id, $category_tax );
$card_terms     = $card_term_list ? join( ' — ', wp_list_pluck( $card_term_list, 'name' ) ) : '';
$heading_level  = 'h3';
$aria_label     = sprintf(
	'%s%s',
	wp_strip_all_tags( $card_title ),
	! empty( $card_terms ) ? " - {$card_terms}" : '',
);

?>

<article <?php echo esc_attr( post_class( implode( ' ', $classnames ), $card_post_id ) ); ?>>

	<?php if ( ! empty( $card_image ) ) : ?>
		<div class="c-card__image">
			<?php echo wp_kses_post( wp_get_attachment_image( $card_image, 'card-thumbnail' ) ); ?>
		</div>
	<?php endif; ?>

	<hr aria-hidden="true" class="wp-block-separator has-text-color has-aubergine-color has-alpha-channel-opacity has-aubergine-background-color has-background">

	<div class="c-card__content">
		<?php
		printf(
			'<a class="c-card__link" href="%1$s" aria-label="%2$s">
				<%3$s class="c-card__heading">
					%4$s
				</%3$s>
			</a>',
			esc_url( $card_link ),
			esc_attr( $aria_label ),
			esc_attr( $heading_level ),
			wp_kses_post( $card_title )
		);
		?>

		<?php if ( ! empty( $card_terms ) ) : ?>
			<p class="c-card__terms">
				<?php echo esc_html( $card_terms ); ?>
			</p>
		<?php endif; ?>
	</div>

</article>
