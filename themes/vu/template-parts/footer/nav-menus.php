<?php
/**
 * Displays the footer navigation menus
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Nav\Nav;

$menus_markup = '';
$footer_menus = [
	Nav::FOOTER_1_NAV,
	Nav::FOOTER_2_NAV,
	Nav::FOOTER_3_NAV,
];

foreach ( $footer_menus as $location ) {

	// Check if the menu location is registered.
	if ( ! has_nav_menu( $location ) ) {
		continue;
	}

	$footer_menu = wp_nav_menu(
		[
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
			'echo'           => false,
		]
	);

	if ( empty( $footer_menu ) ) {
		continue;
	}

	$menu_name  = wp_get_nav_menu_name( $location );
	$menu_label = sprintf(
		'%s %s',
		$menu_name,
		__( 'menu', 'vu' )
	);

	$menus_markup .= '<nav class="c-site-footer__nav" role="navigation" aria-label="' . esc_attr( $menu_label ) . '">';
	$menus_markup .= '<h2 class="c-site-footer__nav-title">' . esc_html( $menu_name ) . '</h2>';
	$menus_markup .= $footer_menu;
	$menus_markup .= '</nav>';
}

if ( empty( $menus_markup ) ) {
	return;
}

?>

<div class="c-site-footer__navs">
	<?php echo wp_kses_post( $menus_markup ); ?>
</div>
