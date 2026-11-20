<?php
/**
 * Displays the Project Nav component
 *
 * @package VUTheme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die();

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( ! $prev_post && ! $next_post ) {
	return;
}

?>

<nav
	aria-label="<?php esc_attr_e( 'Naviga tra i progetti', 'vu' ); ?>"
	class="c-project-nav"
>
	<?php if ( $prev_post ) : ?>
		<a
			class="c-project-nav__prev"
			href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"
			rel="prev"
		>
			<span><?php esc_html_e( '← Precedente', 'vu' ); ?></span>
			<span><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
		</a>
	<?php endif; ?>

	<?php if ( $next_post ) : ?>
		<a
			class="c-project-nav__next"
			href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"
			rel="next"
		>
			<span><?php esc_html_e( 'Successivo →', 'vu' ); ?></span>
			<span><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
		</a>
	<?php endif; ?>
</nav>
