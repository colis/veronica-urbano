<?php
/**
 * Displays the footer copyright
 *
 * @package VUTheme
 * @since 1.0.0
 */

$copyright_text = get_field( 'footer_copyright_text', 'option' ) ?: '';

if ( empty( $copyright_text ) ) {
	return;
}

?>

<div class="c-site-footer__copyright">
	<p>
		<?php echo esc_html( str_replace( '{{year}}', gmdate( 'Y' ), $copyright_text ) ); ?>
	</p>
</div>
