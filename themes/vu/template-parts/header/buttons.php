<?php
/**
 * Displays header buttons
 *
 * @package VUTheme
 * @since 1.0.0
 */

$primary_button = get_field( 'header_primary_button', 'option' ) ?: [];

if ( empty( $primary_button ) ) {
	return;
}

?>

<div class="c-site-header__buttons">
	<a
		href="<?php echo esc_url( $primary_button['url'] ); ?>"
		target="<?php echo esc_attr( $primary_button['target'] ); ?>"
		class="c-site-header__button vu-button"
	>
		<?php echo esc_html( $primary_button['title'] ); ?>
	</a>
</div>
