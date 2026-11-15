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
}
