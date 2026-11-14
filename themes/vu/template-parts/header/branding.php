<?php
/**
 * Displays header site branding
 *
 * @package VUTheme
 * @since 1.0.0
 */

$logo = get_field( 'header_logo', 'option' ) ?: '';

if ( empty( $logo ) ) {
	return;
}

$logo_src = wp_get_attachment_image_src( $logo, 'full' );
$logo_alt = get_post_meta( $logo, '_wp_attachment_image_alt', true );

?>

<div class="c-site-header__branding">

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
				loading="eager"
				fetchpriority="high"
				src="<?php echo esc_url( $logo_src[0] ); ?>"
				width="<?php echo esc_attr( $logo_src[1] ); ?>"
				height="<?php echo esc_attr( $logo_src[2] ); ?>"
			/>
		</a>

	</div>

</div>
