<?php
/**
 * Nav Functionality
 *
 * @package VUCore\Nav
 */

namespace VUCore\Nav;

use VUCore\BaseAbstract;
use VUCore\Core\Icon;
use WP_Post;

/**
 * WordPress Nav Functionality.
 */
class Nav extends BaseAbstract {

	/**
	 * Define constants.
	 */
	const PRIMARY_NAV  = 'primary';
	const FOOTER_1_NAV = 'footer-1';
	const FOOTER_2_NAV = 'footer-2';
	const FOOTER_3_NAV = 'footer-3';
	const LEGAL_NAV    = 'legal';

	/**
	 * ID for a primary nav top-level submenu list element.
	 *
	 * @param int $menu_item_id Menu item post ID.
	 *
	 * @return string
	 */
	public static function primary_submenu_id( int $menu_item_id ): string {
		return 'header-primary-submenu-' . $menu_item_id;
	}

	/**
	 * Register custom navigation locations.
	 *
	 * @return void
	 */
	public function register_nav_menus(): void {
		register_nav_menus(
			[
				self::PRIMARY_NAV  => __( 'Primary Navigation', 'vu-core' ),
				self::FOOTER_1_NAV => __( 'Footer One Navigation', 'vu-core' ),
				self::FOOTER_2_NAV => __( 'Footer Two Navigation', 'vu-core' ),
				self::FOOTER_3_NAV => __( 'Footer Three Navigation', 'vu-core' ),
				self::LEGAL_NAV    => __( 'Legal Navigation', 'vu-core' ),
			],
		);
	}

	/**
	 * WCAG 2.0 Attributes for Dropdown Menus
	 *
	 * Adjustments to menu attributes to support WCAG 2.0 recommendations for flyout and dropdown menus.
	 *
	 * @ref https://www.w3.org/WAI/tutorials/menus/flyout/#use-button-as-toggle
	 *
	 * @param array   $attrs The HTML attributes.
	 * @param WP_Post $item  The current menu item.
	 * @param object  $args  An object of wp_nav_menu() arguments.
	 *
	 * @return array
	 */
	public function nav_menu_submenu_attrs( array $attrs, WP_Post $item, object $args ): array {

		// If this isn't the primary menu location, bail early.
		if ( ! isset( $args->theme_location ) || self::PRIMARY_NAV !== $args->theme_location ) {
			return $attrs;
		}

		// Classes we expect to add attributes to.
		$classes = [
			'menu-item-has-children',
		];

		// Add custom attributes to null links of menu items with children.
		$item_has_children = array_intersect( $classes, $item->classes );
		if ( $item_has_children && '#' === $attrs['href'] ) {
			$attrs['aria-expanded'] = 'false';
		}

		return $attrs;
	}

	/**
	 * Update the Primary navigation menu item HTML markup.
	 *
	 * @param string $output Nav menu item start element.
	 * @param object $item   Nav menu item.
	 * @param int    $depth  Depth.
	 * @param object $args   Nav menu args.
	 *
	 * @return string Nav menu item start element.
	 */
	public function nav_parent_menu_item_markup( string $output, object $item, int $depth, object $args ): string {

		// If this isn't the primary menu location, bail early.
		if ( ! isset( $args->theme_location ) || self::PRIMARY_NAV !== $args->theme_location ) {
			return $output;
		}

		$separator = get_field( 'nav_item_separator', $item ) ? 'separator' : '';
		$sub_link  = get_field( 'nav_item_sub_link', $item ) ? 'sub-link' : '';
		$thumbnail = get_field( 'nav_item_thumbnail', $item ) ?: '';

		// If this menu item features a sub menu.
		if ( array_intersect( [ 'menu-item-has-children' ], $item->classes ) ) {

			// Submenu overlay markup.
			$overlay_markup = '<div class="c-site-header__primary-nav--overlay js-primary-nav-submenu-overlay"></div>';
			$submenu_id     = self::primary_submenu_id( (int) $item->ID );
			$aria_controls  = sprintf( ' aria-controls="%s"', esc_attr( $submenu_id ) );

			// If this menu item is a null link.
			if ( '#' === $item->url ) {
				// Convert link to a button.
				$output = sprintf(
					'<button type="button" class="menu-item-toggle %s %s js-menu-item-toggle" aria-expanded="false"%s>
						<span>%s</span>
						%s
					</button>
					%s',
					esc_html( $separator ),
					esc_html( $sub_link ),
					$aria_controls,
					esc_html( $item->title ),
					Icon::get_svg( 'menu-chevron-right', 32 ),
					wp_kses_post( $overlay_markup ),
				);
			} else {
				// Prepend a button to the menu item.
				$button  = sprintf(
					'<button type="button" class="menu-item-toggle js-menu-item-toggle" aria-expanded="false" aria-label="%s"%s></button>
					%s',
					esc_html( $item->title ),
					$aria_controls,
					wp_kses_post( $overlay_markup ),
				);
				$output .= $button;
			}
		} else {
			// Anchor menu item link.
			$output = sprintf(
				'<a href="%s" class="%s %s">
					%s
					<span>%s</span>
					%s
				</a>',
				esc_url( $item->url ),
				esc_html( $separator ),
				esc_html( $sub_link ),
				! empty( $thumbnail ) ? '<span class="thumbnail"><img data-src="' . esc_url( wp_get_attachment_image_url( $thumbnail, 'menu-thumbnail' ) ) . '" alt=""></span>' : '',
				esc_html( $item->title ),
				Icon::get_svg( 'menu-chevron-right', 32 ),
			);
		}

		return $output;
	}
}
