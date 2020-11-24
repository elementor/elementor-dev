<?php

use ElementorDev\Bootstrap;
use ElementorDev\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$latest_stable_version = Plugin::instance()
	->modules_manager
	->get_modules( 'developer-edition' )
	->version_control
	->get_latest_stable_release();

$elementor_version = get_plugin_data( WP_PLUGIN_DIR . '/' . Bootstrap::ELEMENTOR_PLUGIN_NAME )['Version'];
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

<p><?php
	/* translators: %s: elementor version. */
	echo sprintf( __( 'You are using Elementor %s', 'elementor-dev' ), '<strong>' . $elementor_version . '</strong>' );
?></p>

<?php if ( $should_update_elementor ) : ?>
	<p><?php
		esc_html_e( 'You can update to the latest development builds automatically:', 'elementor-dev' );
	?></p>
<?php endif; ?>

<br/>

<strong>
	<?php
	$should_update_elementor
		? esc_html_e( 'Updated version of Elementor is available.', 'elementor-dev' )
		: esc_html_e( 'Hooray! You’re up to date with the latest versions of Elementor.', 'elementor-dev' );
	?>
</strong>

<br/><br/>

<a
	class="button <?php echo $should_update_elementor ? 'button-primary' : 'button-disabled'; ?>"
	href="<?php echo $update_elementor_url; ?>"
>
	<?php esc_html_e( 'Update Now', 'elementor-dev' ); ?>
</a>

<br/><br/>

<p>
	<?php esc_html_e( 'If you need to re-install the latest stable version, you can do so here:', 'elementor-dev' ); ?>
</p>

<br/>

<a
	class="button <?php echo $should_reinstall_elementor ? '' : 'button-disabled'; ?>"
	href="<?php echo $reinstall_elementor_url; ?>"
>
	<?php esc_html_e( 'Re-install now', 'elementor-dev' ); ?>
</a>

<br/><br/>

<p>
	<?php
	/* translators: %s: Plugin name. */
	echo sprintf( __( '%s is a testing tool for new features and should not be used on live sites. Use staging environments only, and backup all your data before updating.', 'elementor-dev' ), __( 'Elementor Developer Edition', 'elementor-dev' ) );
	?>
	<br/>
	<?php
	/* translators: %s: Learn more link. */
	echo sprintf(
		__( '%s about Elementor Developer Edition', 'elementor-dev' ),
		sprintf( '<a href="https://go.elementor.com/wp-dash-developer-edition" target="_blank">%s</a>', __( 'Learn more', 'elementor-dev' ) )
	)
	?>
	<?php esc_html_e( 'Elementor Developer Edition', 'elementor-dev' ); ?>
</p>

<br/><br/>

<p>
	<?php
	echo sprintf(
		/* translators: %s: email updates link */
		__( '%s to join our first-to-know email updates.', 'elementor-dev' ),
		sprintf( '<a href="#" id="beta-tester-first-to-know">%s</a>', __( 'Click here', 'elementor-dev' ) )
	)
	?>
</p>
