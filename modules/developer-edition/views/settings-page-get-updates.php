<?php

use ElementorBeta\Bootstrap;
use Elementor\Beta_Testers;
use ElementorBeta\Core\Plugin;

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

$should_open_popup = false;
$all_introductions = get_user_meta( get_current_user_id(), 'elementor_introduction', true );

if (
	! is_array( $all_introductions ) ||
	! array_key_exists( Beta_Testers::BETA_TESTER_SIGNUP, $all_introductions )
) {
	$should_open_popup = true;
}
?>

<p><?php
	/* translators: %s: elementor version. */
	echo sprintf( __( 'You are using Elementor %s', 'elementor-beta' ), '<strong>' . $elementor_version . '</strong>' );
?></p>

<?php if ( $should_update_elementor ) : ?>
	<p><?php
		esc_html_e( 'You can update to the latest development builds automatically:', 'elementor-beta' );
	?></p>
<?php endif; ?>

<br/>

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

<br/><br/>

<p>
	<?php
	/* translators: %s: Plugin name. */
	echo sprintf( __( '%s is a testing tool for new features and should not be used on live sites. Use staging environments only, and backup all your data before updating.', 'elementor-beta' ), __( 'Elementor Developer Edition', 'elementor-beta' ) );
	?>
	<br/>
	<?php
	/* translators: %1$s: Learn more link, %2$s: Plugin name */
	echo sprintf(
		__( '%1$s about %2$s.', 'elementor-beta' ),
		sprintf( '<a href="https://go.elementor.com/wp-dash-developer-edition" target="_blank">%s</a>', __( 'Learn more', 'elementor-beta' ) ),
		__( 'Elementor Developer Edition', 'elementor-beta' )
	)
	?>
</p>

<br/><br/>

<p>
	<?php
	echo sprintf(
		/* translators: %s: email updates link */
		__( '%s to join our first-to-know email updates.', 'elementor-beta' ),
		sprintf( '<a href="#" id="beta-tester-first-to-know">%s</a>', __( 'Click here', 'elementor-beta' ) )
	)
	?>
</p>

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
