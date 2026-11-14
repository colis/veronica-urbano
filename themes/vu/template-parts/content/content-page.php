<?php
/**
 * Template part for displaying page content
 *
 * @package VUTheme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die();

?>

<?php do_action( 'vu_theme_page_header' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'vu_theme_page_content_header' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>

	<?php do_action( 'vu_theme_page_content_footer' ); ?>

</article>

<?php do_action( 'vu_theme_page_footer' ); ?>
