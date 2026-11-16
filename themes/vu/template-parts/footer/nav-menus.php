<?php
/**
 * Displays the footer navigation menus
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Core\Icon;
use VUCore\Nav\Nav;

$menus_markup = '';
$footer_menus = [
	Nav::FOOTER_1_NAV,
	Nav::FOOTER_2_NAV,
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
	$menus_markup .= $footer_menu;
	$menus_markup .= '</nav>';
}

if ( empty( $menus_markup ) ) {
	return;
}

$icon_uri   = Icon::get_data_uri( 'arrow-top-right' );
$icon_style = $icon_uri ? sprintf( '--icon-url: url("%s");', $icon_uri ) : '';

?>

<div class="c-site-footer__navs" style="<?php echo esc_attr( $icon_style ); ?>">
	<?php get_template_part( 'template-parts/footer/branding' ); ?>
	<?php echo wp_kses_post( $menus_markup ); ?>
</div>
