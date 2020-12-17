<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<label>
	<input
		type="checkbox"
		id="elementor_allow_tracking"
		name="elementor_allow_tracking"
		value="yes"
		<?php checked( get_option( 'elementor_allow_tracking', false ), 'yes' ); ?>
	>
	<?php esc_html_e( 'Become a super contributor by opting in to share non-sensitive plugin data and to receive periodic email updates from us.', 'elementor-beta' ); ?>
	<a href="https://go.elementor.com/usage-data-tracking/" target="_blank"> <?php esc_html_e( 'Learn more', 'elementor-beta' ); ?> </a>
</label>
