<?php
/**
 * Displays hamburger button
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Nav\Nav;

$open_menu_label       = __( 'Menu - open main menu', 'vu' );
$close_menu_label      = __( 'Menu - close main menu', 'vu' );
$has_primary_nav       = has_nav_menu( Nav::PRIMARY_NAV );
$has_primary_nav_items = wp_nav_menu(
	[
		'theme_location' => Nav::PRIMARY_NAV,
		'fallback_cb'    => false,
		'echo'           => false,
	]
);

if ( ! $has_primary_nav || ! $has_primary_nav_items ) {
	return;
}

?>

<button
	id="primary-toggle-menu"
	class="c-site-header__hamburger"
	aria-haspopup="true"
	aria-expanded="false"
	aria-controls="header-primary-menu"
	aria-label="<?php echo esc_attr( $open_menu_label ); ?>"
	data-open-label="<?php echo esc_attr( $open_menu_label ); ?>"
	data-close-label="<?php echo esc_attr( $close_menu_label ); ?>"
>
	<span class="line"></span>
	<span class="line"></span>
	<span class="line"></span>
</button>
