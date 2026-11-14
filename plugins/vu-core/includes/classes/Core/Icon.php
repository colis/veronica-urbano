<?php
/**
 * Icon Functionality
 *
 * @package VUCore\Core
 */

namespace VUCore\Core;

use VUCore\BaseAbstract;

/**
 * Icon Functionality.
 */
class Icon extends BaseAbstract {

	/**
	 * Static cache for prepared SVG output.
	 *
	 * Keyed by "{$icon}-{$width}-{$height}".
	 *
	 * @var array<string, string|null>
	 */
	private static array $svg_cache = [];

	/**
	 * Static cache for custom icons.
	 *
	 * @var array|null
	 */
	private static ?array $custom_icons_cache = null;

	/**
	 * Array of VU glyphs / icons.
	 *
	 * @var array
	 */
	public static $vu_icons = [
		'chevron-down' => [
			'svg'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.74612 8.86612C5.23428 8.37796 6.02573 8.37796 6.51389 8.86612L12.0028 14.355L17.4917 8.86612C17.9799 8.37796 18.7713 8.37796 19.2595 8.86612C19.7476 9.35427 19.7476 10.1457 19.2595 10.6339L12.8867 17.0067C12.3985 17.4948 11.6071 17.4948 11.1189 17.0067L4.74612 10.6339C4.25797 10.1457 4.25797 9.35427 4.74612 8.86612Z" fill="currentColor"/></svg>',
			'category' => 'Glyphs',
		],
	];

	/**
	 * Retrieves custom icons defined in site options and prepares them for use.
	 *
	 * @return array An associative array of custom icon data.
	 */
	public static function get_custom_icons(): array {
		if ( null !== self::$custom_icons_cache ) {
			return self::$custom_icons_cache;
		}

		// Attempt to retrieve from cache first.
		$cached = wp_cache_get( 'custom_icons', VU_CORE_CACHE_GROUP );
		if ( is_array( $cached ) ) {
			self::$custom_icons_cache = $cached;
			return $cached;
		}

		$prepared_icons = [];
		$custom_icons   = get_field( 'custom_icons', 'option' ) ?? [];

		// If there are no custom icons defined, bail early.
		if ( empty( $custom_icons ) ) {
			return [];
		}

		// Prepare icons for output and cache the results.
		foreach ( $custom_icons as $icon_data ) {
			$slug                    = $icon_data['slug'] ?? '';
			$svg_source              = $icon_data['icon'] ?? '';
			$prepared_icons[ $slug ] = [
				'svg'      => $svg_source,
				'category' => 'Custom',
			];
		}

		wp_cache_set( 'custom_icons', $prepared_icons, VU_CORE_CACHE_GROUP, 300 );
		self::$custom_icons_cache = $prepared_icons;

		return $prepared_icons;
	}

	/**
	 * Prepares raw SVG markup for output.
	 *
	 * - Ensures decorative a11y attributes exist on the root <svg> element.
	 * - Removes newlines/tabs and whitespace between SVG tags.
	 * - Resets default color references to "currentColor" for color support.
	 *
	 * @param string   $svg_source Raw SVG markup.
	 * @param string   $icon       Optional icon slug (used as an additional CSS class).
	 * @param int      $width      Optional width attribute (defaults to 20).
	 * @param int|null $height     Optional height attribute (defaults to width if not provided).
	 *
	 * @return string|null Prepared SVG markup or null if invalid.
	 */
	public static function prepare_svg( string $svg_source, string $icon = '', int $width = 20, ?int $height = null ): ?string {
		$svg_source = trim( $svg_source );

		if ( empty( $svg_source ) ) {
			return null;
		}

		$svg_start_pos = stripos( $svg_source, '<svg' );
		if ( false === $svg_start_pos ) {
			return null;
		}

		$svg_source = substr( $svg_source, $svg_start_pos );

		$icon   = trim( $icon );
		$width  = $width > 0 ? $width : 20;
		$height = null !== $height && $height > 0 ? $height : $width;

		// Drop asset dimensions so only get_svg() width/height are output.
		$svg_source = preg_replace( '/\s+width=(["\'])[^"\']*\1/i', '', $svg_source, 1 );
		$svg_source = preg_replace( '/\s+height=(["\'])[^"\']*\1/i', '', $svg_source, 1 );

		$repl = sprintf(
			'<svg class="svg-icon %s" width="%d" height="%d" aria-hidden="true" focusable="false" ',
			esc_attr( $icon ),
			$width,
			$height
		);

		// Add extra attributes to root SVG.
		$svg = preg_replace( '/^<svg\b\s*/i', $repl, $svg_source, 1 );

		// Remove newlines & tabs.
		$svg = preg_replace( "/([\n\t]+)/", ' ', $svg );

		// Remove white space between SVG tags.
		$svg = preg_replace( '/>\s*</', '><', $svg );

		// If the markup features a stroke (non-none), normalize stroke colors;
		// otherwise normalize fill colors. Preserve `none`/`currentColor` and
		// skip URL/var()-based values (gradients, CSS vars).
		$uses_stroke = (bool) preg_match( '/\bstroke\s*=\s*(["\'])\s*(?!none\b)(?!currentcolor\b)([^"\']+)\1/i', $svg );
		$attr_name   = $uses_stroke ? 'stroke' : 'fill';
		$svg         = preg_replace_callback(
			sprintf( '/\\b%s\\s*=\\s*(["\'])\\s*([^"\']*)\\1/i', preg_quote( $attr_name, '/' ) ),
			function ( $matches ) use ( $attr_name ) {
				$quote = $matches[1];
				$value = trim( $matches[2] );
				$lower = strtolower( $value );

				if ( '' === $value || 'none' === $lower || 'currentcolor' === $lower ) {
					return $matches[0];
				}

				if ( 0 === strpos( $lower, 'url(' ) || 0 === strpos( $lower, 'var(' ) ) {
					return $matches[0];
				}

				return sprintf( '%s=%scurrentColor%s', $attr_name, $quote, $quote );
			},
			$svg
		);

		return $svg;
	}

	/**
	 * Gets the SVG code for a given registry or custom icon.
	 *
	 * @param string   $icon   The icon name.
	 * @param int      $width  The icon width. Default is 20px.
	 * @param int|null $height The icon height. Defaults to width if not provided.
	 *
	 * @return string|null The SVG code or null if not found.
	 */
	public static function get_svg( $icon, $width = 20, $height = null ) {

		$height     = $height ?? $width;
		$svg_source = '';
		$cache_key  = "{$icon}-{$width}-{$height}";

		// Check if the SVG markup is already cached.
		if ( array_key_exists( $cache_key, self::$svg_cache ) ) {
			return self::$svg_cache[ $cache_key ];
		}

		// Check if the icon exists in the VU icons array.
		if ( array_key_exists( $icon, self::$vu_icons ) ) {
			$icon_entry = self::$vu_icons[ $icon ];
			$svg_source = is_array( $icon_entry ) ? ( $icon_entry['svg'] ?? '' ) : $icon_entry;
		} else {
			// If not, check the custom icons array (site options).
			$custom_icons = self::get_custom_icons();
			if ( array_key_exists( $icon, $custom_icons ) ) {
				$icon_entry = $custom_icons[ $icon ];
				$svg_source = $icon_entry['svg'] ?? '';
			}
		}

		if ( empty( $svg_source ) ) {
			self::$svg_cache[ $cache_key ] = null;
			return null;
		}

		$result                        = self::prepare_svg( $svg_source, $icon, $width, $height );
		self::$svg_cache[ $cache_key ] = $result;

		return $result;
	}
}
