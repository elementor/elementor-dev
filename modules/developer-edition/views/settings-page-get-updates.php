<?php

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
$elementor_pro_version = $module->pro_version_control->get_current_version();
?>

<p><?php
/* translators: %s: elementor version. */
echo sprintf( __( 'You are using Elementor %s', 'elementor-beta' ), '<strong>' . $elementor_version . '</strong>' );

if ( $elementor_pro_version ) {
		/* translators: %s: elementor pro version. */
		echo ', ';
		echo sprintf( __( 'and Elementor Pro %s', 'elementor-beta' ), '<strong>' . $elementor_pro_version . '</strong>' );
}
?></p>

<p>
	<?php esc_html_e( 'You can update to the latest development builds automatically:', 'elementor-beta' ); ?>
</p>
