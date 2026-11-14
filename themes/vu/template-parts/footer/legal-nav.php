<?php
/**
 * Displays the footer socials and legal navigation
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Nav\Nav;

$legal_nav = wp_nav_menu(
	[
		'theme_location' => Nav::LEGAL_NAV,
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'echo'           => false,
	]
);

if ( empty( $legal_nav ) ) {
	return;
}

?>

<nav class="legal-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Legal menu', 'vu' ); ?>">
	<?php echo wp_kses_post( $legal_nav ); ?>
</nav>
