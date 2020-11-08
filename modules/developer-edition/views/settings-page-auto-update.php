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
	<?php
	echo sprintf(
		/* translators: %s: Plugin name. */
		__( 'When activating %s, the auto updates for the installed versions will be enabled unless you uncheck this checkbox or opt-out manually in the Plugins screen.', 'elementor-dev' ),
		__( 'Elementor Developer Edition', 'elementor-dev' )
	);
	?>
</p>
