<?php
/**
 * Primary Nav Walker
 *
 * @package VUCore\Nav
 */

namespace VUCore\Nav;

use stdClass;
use Walker_Nav_Menu;
use WP_Post;

/**
 * Custom Walker for Primary Navigation
 */
class PrimaryNavWalker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 *
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Set the parent menu item ID in the args.
		$args->menu_item_parent = $args->current_item_id ?? 0;

		$classes        = [ 'sub-menu' ];
		$class_names    = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names    = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$component_type = 'data-component-type="sub_nav"';
		$id_attr        = '';

		if ( 0 === $depth && ! empty( $args->current_item_id ) ) {
			$id_attr = ' id="' . esc_attr( Nav::primary_submenu_id( (int) $args->current_item_id ) ) . '"';
		}

		$output .= "{$n}{$indent}<ul$class_names {$id_attr} {$component_type}>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 *
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Maybe add widgets before closing the submenu.
		$widget_content = $this->get_submenu_widgets( $args );
		if ( $widget_content ) {
			$output .= $widget_content;
		}

		$output .= "{$indent}</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 *
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Store the current item ID in the args.
		$args->current_item_id = $item->ID;

		parent::start_el( $output, $item, $depth, $args, $id );
	}

	/**
	 * Get widgets for a menu item.
	 *
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 *
	 * @return string HTML for widget list items.
	 */
	private function get_submenu_widgets( $args ): string {
		if ( ! isset( $args->menu_item_parent ) || ! $args->menu_item_parent ) {
			return '';
		}

		// Check if the parent menu item has widgets enabled.
		$has_widgets = get_field( 'nav_item_widgets', $args->menu_item_parent ) ?: false;

		if ( empty( $has_widgets ) ) {
			return '';
		}

		// Get the submenu widgets from site options.
		$widgets = get_field( 'header_nav_widgets', 'option' )['widgets'] ?? [];

		if ( empty( $widgets ) ) {
			return '';
		}

		// Build the widgets markup.
		$widget_html = '<li class="menu-item c-menu-item-widgets">' . "\n";

		if ( ! empty( $widgets['label'] ) ) {
			$widget_html .= '<div class="c-menu-item-widgets__label">' . esc_html( $widgets['label'] ) . '</div>' . "\n";
		}

		$widget_html .= '<div class="c-menu-item-widgets__widgets">' . "\n";

		foreach ( $widgets as $widget ) {
			$widget_html .= '<a href="' . esc_url( $widget['link']['url'] ) . '" class="c-menu-item-widget__widget">' . "\n";
			$widget_html .= '<div class="c-menu-item-widget__widget--image"><img data-src="' . esc_url( wp_get_attachment_image_url( $widget['image'], 'medium' ) ) . '" alt=""></div>' . "\n";
			$widget_html .= '<div class="c-menu-item-widget__widget--title">' . esc_html( $widget['link']['title'] ) . '</div>' . "\n";
			$widget_html .= '</a>' . "\n";
		}

		$widget_html .= '</div>' . "\n";
		$widget_html .= '</li>' . "\n";

		return $widget_html;
	}
}
