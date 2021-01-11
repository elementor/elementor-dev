<?php

use ElementorBeta\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$should_update_elementor_pro = array_key_exists( Bootstrap::ELEMENTOR_PRO_PLUGIN_NAME, get_plugin_updates() );

$update_elementor_pro_url = $should_update_elementor_pro
	? wp_nonce_url(
		self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . Bootstrap::ELEMENTOR_PRO_PLUGIN_NAME ),
		'upgrade-plugin_' . Bootstrap::ELEMENTOR_PRO_PLUGIN_NAME
	)
	: '#';
?>

<strong>
	<?php
	$should_update_elementor_pro
		? esc_html_e( 'Updated version of Elementor Pro is available.', 'elementor-beta' )
		: esc_html_e( 'Hooray! You’re up to date with the latest versions of Elementor Pro.', 'elementor-beta' );
	?>
</strong>

<br/><br/>

<a
	class="button <?php echo $should_update_elementor_pro ? 'button-primary' : 'button-disabled'; ?>"
	href="<?php echo $update_elementor_pro_url; ?>"
	data-loading-text="<?php esc_html_e( 'Updating...', 'elementor-beta' ); ?>"
>
	<?php esc_html_e( 'Update Now', 'elementor-beta' ); ?>
</a>

<br /><br />

<p>
	<?php
	echo sprintf(
		__( 'If you wish to revert to the latest stable Pro version please use %s', 'elementor-beta' ),
		'<a href="' . admin_url( 'admin.php?page=elementor-tools#tab-versions' ) . '">' . __( 'Elementor Rollback screen.', 'elementor-beta' ) . '</a>'
	)
	?>
</p>
