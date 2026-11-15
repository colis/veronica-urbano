<?php
/**
 * Displays the footer site branding
 *
 * @package VUTheme
 * @since 1.0.0
 */

$logo     = get_field( 'footer_logo', 'option' ) ?: '';
$logo_src = wp_get_attachment_image_src( $logo, 'full' );
$logo_alt = get_post_meta( $logo, '_wp_attachment_image_alt', true );

$footer_message = get_field( 'footer_message', 'option' ) ?: '';
$footer_link    = get_field( 'footer_link', 'option' ) ?: [];

?>

<div class="c-site-footer__branding">

	<?php if ( ! empty( $logo ) ) : ?>
		<div class="c-site-logo">
			<a
				href="<?php echo esc_url( home_url() ); ?>"
				rel="home"
				aria-label="<?php echo esc_attr( sprintf( '%s %s', get_bloginfo( 'name' ), __( 'Home', 'vu' ) ) ); ?>"
			>
				<img
					alt="<?php echo esc_attr( $logo_alt ); ?>"
					class="site-logo"
					decoding="async"
					loading="lazy"
					src="<?php echo esc_url( $logo_src[0] ); ?>"
					width="<?php echo esc_attr( $logo_src[1] ); ?>"
					height="<?php echo esc_attr( $logo_src[2] ); ?>"
				/>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $footer_message ) ) : ?>
		<p class="c-site-footer__branding-message">
			<?php echo esc_html( $footer_message ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $footer_link ) ) : ?>
		<a
			class="c-site-footer__branding-link"
			href="<?php echo esc_url( $footer_link['url'] ); ?>"
			target="<?php echo esc_attr( $footer_link['target'] ?: '_self' ); ?>"
		>
			<?php echo esc_html( $footer_link['title'] ); ?>
		</a>
	<?php endif; ?>
</div>
