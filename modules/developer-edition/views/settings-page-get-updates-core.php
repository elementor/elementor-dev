<?php

use ElementorBeta\Bootstrap;
use ElementorBeta\Core\Plugin;
use ElementorBeta\Modules\DeveloperEdition\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var Module $module */
$module = Plugin::instance()
	->modules_manager
	->get_modules( 'developer-edition' );

$core_version_control = $module->core_version_control;

$elementor_version = $module->core_version_control->get_current_version();

$latest_stable_version = $core_version_control->get_latest_stable_release();

$should_update_elementor = array_key_exists( Bootstrap::ELEMENTOR_PLUGIN_NAME, get_plugin_updates() );
$should_reinstall_elementor = ! version_compare( $latest_stable_version, $elementor_version, '=' );

$update_elementor_url = $should_update_elementor
	? wp_nonce_url(
		self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . Bootstrap::ELEMENTOR_PLUGIN_NAME ),
		'upgrade-plugin_' . Bootstrap::ELEMENTOR_PLUGIN_NAME
	)
	: '#';

$reinstall_elementor_url = $should_reinstall_elementor
	? wp_nonce_url(
		self_admin_url( 'admin-post.php?action=elementor_rollback&version=' . $latest_stable_version ),
		'elementor_rollback'
	)
	: '#';
?>

<strong>
	<?php
	$should_update_elementor
		? esc_html_e( 'Updated version of Elementor is available.', 'elementor-beta' )
		: esc_html_e( 'Hooray! You’re up to date with the latest versions of Elementor.', 'elementor-beta' );
	?>
</strong>

<br/><br/>

<a
	class="button <?php echo $should_update_elementor ? 'button-primary' : 'button-disabled'; ?>"
	href="<?php echo $update_elementor_url; ?>"
	data-loading-text="<?php esc_html_e( 'Updating...', 'elementor-beta' ); ?>"
>
	<?php esc_html_e( 'Update Now', 'elementor-beta' ); ?>
</a>

<br/><br/>

<p>
	<?php esc_html_e( 'If you need to re-install the latest stable version, you can do so here:', 'elementor-beta' ); ?>
</p>

<br/>

<a
	class="button <?php echo $should_reinstall_elementor ? '' : 'button-disabled'; ?>"
	href="<?php echo $reinstall_elementor_url; ?>"
	data-loading-text="<?php esc_html_e( 'Re-installing...', 'elementor-beta' ); ?>"
>
	<?php esc_html_e( 'Re-install Now', 'elementor-beta' ); ?>
</a>
