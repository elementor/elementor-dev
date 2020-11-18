<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<label for="elementor-dev-auto-update">
	<input
		type="checkbox"
		id="elementor_dev_auto_update"
		name="elementor_dev_auto_update"
		value="yes"
		<?php checked( get_option( 'elementor_dev_auto_update', false ), 'yes' ); ?>
	/>
	<?php esc_html_e( 'Activate Auto Updates', 'elementor-dev' ); ?>
</label>
<br/><br/>
<p style="max-width: 900px;">
	<?php esc_html_e( 'Activating auto updates will periodically update your Elementor plugin to the latest available release.', 'elementor-dev' ); ?>
</p>
