<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package VUTheme
 * @since 1.0.0
 */

get_header();
?>

	<section id="primary" class="c-content-area">
		<main id="main" class="c-site-main" role="main">

		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content', 'page' );
			}
		}
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php
get_footer();
