<?php
/**
 * Displays the footer copyright
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Nav\Nav;

$menu_markup    = '';
$copyright_menu = wp_nav_menu(
	[
		'theme_location' => Nav::COPYRIGHT_NAV,
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'echo'           => false,
	]
);

$menu_name  = wp_get_nav_menu_name( Nav::COPYRIGHT_NAV );
$menu_label = sprintf(
	'%s %s',
	$menu_name,
	__( 'menu', 'vu' )
);

$menu_markup .= '<nav class="c-site-footer__copyright-nav" role="navigation" aria-label="' . esc_attr( $menu_label ) . '">';
$menu_markup .= $copyright_menu;
$menu_markup .= '</nav>';

if ( empty( $menu_markup ) ) {
	return;
}

$copyright_text = get_field( 'footer_copyright_text', 'option' ) ?: '';

?>

<div class="c-site-footer__copyright">
	<div class="c-site-footer__copyright-left">
		<?php if ( ! empty( $copyright_text ) ) : ?>
			<p>
				<?php echo esc_html( str_replace( '{{year}}', gmdate( 'Y' ), $copyright_text ) ); ?>
			</p>
		<?php endif; ?>

		<?php echo wp_kses_post( $menu_markup ); ?>
	</div>

	<a href="https://github.com/colis" target="_blank">
		<?php esc_html_e( 'Developed by a lazy programmer', 'vu' ); ?>
	</a>
</div>
