<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package VUTheme
 * @since 1.0.0
 */

$page_404 = get_field( '404_page', 'option' );

if ( ! $page_404 instanceof WP_Post ) {
	return;
}

get_header();
?>

	<section id="primary" class="c-content-area">
		<main id="main" class="c-site-main c-404-main" role="main">

			<?php echo apply_filters( 'the_content', $page_404->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();
