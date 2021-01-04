<?php

use Elementor\Beta_Testers;
use ElementorBeta\Modules\DeveloperEdition\Module;
use ElementorBeta\Modules\DeveloperEdition\Settings_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$should_open_popup = false;
$all_introductions = get_user_meta( get_current_user_id(), 'elementor_introduction', true );

if (
	! is_array( $all_introductions ) ||
	! array_key_exists( Beta_Testers::BETA_TESTER_SIGNUP, $all_introductions )
) {
	$should_open_popup = true;
}
?>

	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p style="max-width: 750px;">
			<?php esc_html_e(
				'This plugin gives you direct access into Elementor’s development process, and lets you take an active part in perfecting our product. Each Developer Edition release will contain experimental functionalities that developers will be able to use to get familiar with the next releases before they are published.',
				'elementor-beta'
			); ?>
		</p>
		<form action="options.php" method="post">
			<br/>
			<?php
			settings_fields( Module::SETTINGS_KEY );
			do_settings_sections( Settings_Page::PAGE_ID );
			submit_button();
			?>
		</form>
	</div>

<?php if ( $should_open_popup ) : ?>
	<script>
		document.addEventListener( 'DOMContentLoaded', () => {
			if ( ! window.elementorBetaTester ) {
				return;
			}

			window.elementorBetaTester.showLayout( true )
		} );
	</script>
<?php endif; ?>

	<script>
		document.querySelectorAll( 'a[data-loading-text]' ).forEach( ( el ) => {
			el.addEventListener( 'click', ( e ) => {
				if ( e.target.classList.contains( 'button-disabled' ) ) {
					e.preventDefault();

					return;
				}

				e.target.classList.add( 'button-disabled' );
				e.target.innerHTML = e.target.dataset.loadingText;
			} )
		} );
	</script>
<?php
