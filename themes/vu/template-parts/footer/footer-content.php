<?php
/**
 * Displays the footer content
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Core\Icon;

$brand_mark = get_field( 'footer_brand_mark', 'option' ) ?: '';

$icon_uri   = Icon::get_data_uri( 'arrow-top-right' );
$icon_style = $icon_uri ? sprintf( '--icon-url: url("%s");', $icon_uri ) : '';

?>

<footer id="colophon" class="c-site-footer" role="contentinfo" aria-label="<?php esc_attr_e( 'Footer', 'vu' ); ?>" style="<?php echo esc_attr( $icon_style ); ?>">

	<div class="c-site-footer__main">
		<div class="container">

			<div class="c-site-footer__main--body">
				<?php get_template_part( 'template-parts/footer/nav-menus' ); ?>
			</div>

			<?php get_template_part( 'template-parts/footer/copyright' ); ?>

		</div>
	</div>

</footer><!-- #colophon -->
