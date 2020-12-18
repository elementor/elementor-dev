<?php
use ElementorBeta\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="notice notice-error" id="elementor-not-active">
	<p>
		<?php
		echo sprintf(
			esc_html__(
				/* translators: %s: Name of plugin */
				'%s requires Elementor to be activate.',
				'elementor-beta'
			),
			'<strong>' . esc_html__( 'Elementor Developer Edition', 'elementor-beta' ) . '</strong>'
		);
		?>
	</p>
	<p>
		<?php
		$activate_url = esc_url(
			wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . Bootstrap::ELEMENTOR_PLUGIN_NAME . '&plugin_status=active' ),
				'activate-plugin_' . Bootstrap::ELEMENTOR_PLUGIN_NAME
			)
		);
		?>

		<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary">
			<?php echo esc_html__( 'Activate', 'elementor-beta' ); ?>
		</a>
	</p>
</div>
