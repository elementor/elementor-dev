<?php

use ElementorDev\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_checked = false;
$auto_updates = (array) get_site_option( 'auto_update_plugins', [] );

if ( in_array( Bootstrap::ELEMENTOR_PLUGIN_NAME, $auto_updates, true ) ) {
	$is_checked = true;
}
?>
<label for="elementor-dev-auto-update">
	<input
		type="checkbox"
		id="elementor_dev_auto_update"
		name="elementor_dev_auto_update"
		value="yes"
		<?php checked( $is_checked ); ?>
	/>
	<?php esc_html_e( 'Activate Auto Updates', 'elementor-dev' ); ?>
</label>
<br/><br/>
<p style="max-width: 900px;">
	<?php esc_html_e( 'Activating auto updates will periodically update your Elementor plugin to the latest available release.', 'elementor-dev' ); ?>
</p>
