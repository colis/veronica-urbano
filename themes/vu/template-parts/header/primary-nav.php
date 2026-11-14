<?php
/**
 * Displays the Primary Navigation
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Nav\Nav;
use VUCore\Nav\PrimaryNavWalker;

$primary_nav = wp_nav_menu(
	[
		'theme_location' => Nav::PRIMARY_NAV,
		'walker'         => new PrimaryNavWalker(),
		'menu_class'     => 'menu-wrapper',
		'container'      => false,
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'          => 2,
		'fallback_cb'    => false,
		'echo'           => false,
	]
);

if ( empty( $primary_nav ) ) {
	return;
}

?>

<nav
	id="header-primary-menu"
	class="c-site-header__primary-nav"
	role="navigation"
	aria-label="<?php esc_attr_e( 'Primary navigation', 'vu' ); ?>"
>
	<?php echo $primary_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped. ?>
</nav>
