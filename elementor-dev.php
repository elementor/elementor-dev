<?php
/**
 * Plugin Name: Elementor Dev
 * Plugin URI: https://elementor.com
 * Description: Run bleeding edge versions of Elementor. This will replace your installed version of Elementor with the latest tagged release - use with caution, and not on production sites.
 * Version: 1.0.0
 * Author: Elementor.com
 * Author URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=dev-author-uri&utm_medium=wp-dash
 * Text Domain: elementor-dev
 *
 * @package Elementor_Dev
 */

use ElementorDev\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ELEMENTOR_DEV_FILE' ) ) {
	define( 'ELEMENTOR_DEV_FILE', __FILE__ );
}

if ( ! defined( 'ELEMENTOR_DEV_DIR' ) ) {
	define( 'ELEMENTOR_DEV_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ELEMENTOR_DEV_NAMESPACE' ) ) {
	define( 'ELEMENTOR_DEV_NAMESPACE', 'ElementorDev' );
}

if ( ! defined( 'ELEMENTOR_DEV_VERSION' ) ) {
	define( 'ELEMENTOR_DEV_VERSION', '1.0.0' );
}

add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'elementor-dev' );
} );


add_action( 'plugins_loaded', function () {
	require_once dirname( __FILE__ ) . '/core/plugin.php';

	$plugin = Plugin::instance();

	if ( ! $plugin->can_bootstrap() ) {
		return;
	}

	$plugin->bootstrap();
} );
