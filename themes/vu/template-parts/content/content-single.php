<?php
/**
 * Template part for displaying single post content
 *
 * @package VUTheme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die();

$the_post_type = get_post_type();

$content = get_the_content(
	sprintf(
		wp_kses(
			/* translators: %s: Name of current post. Only visible to screen readers */
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cfa' ),
			[
				'span' => [
					'class' => [],
				],
			]
		),
		get_the_title()
	)
);

?>

<?php do_action( 'vu_theme_single_header' ); ?>
<?php do_action( "vu_theme_{$the_post_type}_single_header" ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( "vu_theme_{$the_post_type}_single_content_header" ); ?>
	<?php do_action( 'vu_theme_single_content_header' ); ?>

	<div class="entry-content-container">

		<?php do_action( 'vu_theme_single_content_sidebar' ); ?>
		<?php do_action( "vu_theme_{$the_post_type}_single_content_sidebar" ); ?>

		<div class="entry-content">
			<?php
			do_action( 'vu_theme_single_content_before' );
			do_action( "vu_theme_{$the_post_type}_single_content_before" );

			echo apply_filters( 'the_content', $content ); // phpcs:ignore

			do_action( 'vu_theme_single_content_after' );
			do_action( "vu_theme_{$the_post_type}_single_content_after" );
			?>
		</div>

	</div>

	<?php do_action( 'vu_theme_single_content_footer' ); ?>
	<?php do_action( "vu_theme_{$the_post_type}_single_content_footer" ); ?>

</article>

<?php do_action( 'vu_theme_single_footer' ); ?>
<?php do_action( "vu_theme_{$the_post_type}_single_footer" ); ?>
