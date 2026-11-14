<?php
/**
 * Displays header component
 *
 * @package VUTheme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || die();

use VUCore\Core\Icon;

?>

<header id="masthead" class="c-site-header" role="banner">

	<div class="c-site-header__content js-header-content">
		<div class="container">

			<?php get_template_part( 'template-parts/header/hamburger' ); ?>
			<?php get_template_part( 'template-parts/header/branding' ); ?>

			<div class="c-site-header__nav js-primary-mobile-drawer">
				<div class="c-site-header__nav--inner">
					<button type="button" class="c-site-header__mobile-back js-primary-mobile-drawer-back">
						<?php echo Icon::get_svg( 'menu-chevron-right', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<?php

					// Include Primary Navigation for desktop & mobile.
					get_template_part( 'template-parts/header/primary-nav' );

					?>
				</div>

			</div>

			<?php get_template_part( 'template-parts/header/buttons' ); ?>

		</div>
	</div>

</header>
