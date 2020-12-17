<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="notice notice-error" id="elementor-not-installed">
	<p>
		<?php
		echo sprintf(
			esc_html__(
				/* translators: %s: Name of plugin */
				'%s requires Elementor for functioning properly. Please Install and Activate Elementor plugin',
				'elementor-beta'
			),
			'<strong>' . esc_html__( 'Elementor Developer Edition', 'elementor-beta' ) . '</strong>'
		);
		?>
	</p>

	<?php
	$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
	?>

	<p>
		<a href="<?php echo esc_url( $install_url ); ?>" class="button button-primary">
			<?php echo esc_html__( 'Install & Activate', 'elementor-beta' ); ?>
		</a>
	</p>
</div>

