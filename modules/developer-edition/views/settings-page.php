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
				'This plugin gives you direct access into Elementor’s development process, and lets you take an active part in perfecting our product. Each Developer Edition release will contain experimental functionalities that developers will be able to use to get familiar with the next releases before they are published.',
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
