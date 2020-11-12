<?php

use ElementorDev\Modules\DeveloperEdition\Module;
use ElementorDev\Modules\DeveloperEdition\Settings_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p style="max-width: 750px;">
			<?php esc_html_e(
				'Get a sneak peek at our in progress development versions, and help us improve Elementor to perfection. Developer Edition releases contain experimental functionality for testing purposes. This channel will also include RC and Beta releases.',
				'elementor-dev'
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
<?php
