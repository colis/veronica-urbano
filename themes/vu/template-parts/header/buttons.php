<?php
/**
 * Displays header buttons
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\Core\Icon;

$primary_button = get_field( 'header_primary_button', 'option' ) ?: [];

if ( empty( $primary_button ) ) {
	return;
}

$icon_uri   = Icon::get_data_uri( 'arrow-right' );
$icon_style = $icon_uri
	? sprintf( '--icon-url: url("%s");', $icon_uri )
	: '';

$button_classes = [
	'c-site-header__button',
	'vu-button',
];

if ( ! empty( $icon_uri ) ) {
	$button_classes = [
		...$button_classes,
		'has-icon',
		'has-icon-trailing',
	];
}

?>

<div class="c-site-header__buttons">
	<a
		href="<?php echo esc_url( $primary_button['url'] ); ?>"
		target="<?php echo esc_attr( $primary_button['target'] ); ?>"
		class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>"
		style="<?php echo esc_attr( $icon_style ); ?>"
	>
		<?php echo esc_html( $primary_button['title'] ); ?>
	</a>
</div>
