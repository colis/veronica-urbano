<?php
/**
 * Displays the footer content
 *
 * @package VUTheme
 * @since 1.0.0
 */

$brand_mark = get_field( 'footer_brand_mark', 'option' ) ?: '';

?>

<footer id="colophon" class="c-site-footer" role="contentinfo" aria-label="<?php esc_attr_e( 'Footer', 'vu' ); ?>">

	<div class="c-site-footer__main">
		<div class="container">

			<div class="c-site-footer__main--body">
				<?php get_template_part( 'template-parts/footer/nav-menus' ); ?>
			</div>

			<?php get_template_part( 'template-parts/footer/copyright' ); ?>

		</div>
	</div>

</footer><!-- #colophon -->
