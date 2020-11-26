<?php
/**
 * Plugin Name: Elementor Developer Edition
 * Plugin URI: https://elementor.com
 * Description: Elementor Developer Edition gives you direct access into Elementor’s development process, and lets you take an active part in perfecting our product. Each Developer Edition release will contain experimental functionalities that developers will be able to use to get familiar with the next releases before they are published.
 * Version: 1.0.0
 * Author: Elementor.com
 * Author URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=dev-author-uri&utm_medium=wp-dash
 * Text Domain: elementor-dev
 *
 * @package Elementor_Dev
 */

use ElementorDev\Bootstrap;
use ElementorDev\Autoloader;
use ElementorDev\Modules\DeveloperEdition\Admin_Bar;
use ElementorDev\Modules\DeveloperEdition\Version_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ELEMENTOR_DEV_FILE' ) ) {
	define( 'ELEMENTOR_DEV_FILE', __FILE__ );
}

if ( ! defined( 'ELEMENTOR_DEV_BASENAME' ) ) {
	define( 'ELEMENTOR_DEV_BASENAME', plugin_basename( ELEMENTOR_DEV_FILE ) );
}

if ( ! defined( 'ELEMENTOR_DEV_DIR' ) ) {
	define( 'ELEMENTOR_DEV_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ELEMENTOR_DEV_URL' ) ) {
	define( 'ELEMENTOR_DEV_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ELEMENTOR_DEV_NAMESPACE' ) ) {
	define( 'ELEMENTOR_DEV_NAMESPACE', 'ElementorDev' );
}

if ( ! defined( 'ELEMENTOR_DEV_VERSION' ) ) {
	define( 'ELEMENTOR_DEV_VERSION', '1.0.0' );
}

// Run autoloader
require_once __DIR__ . '/autoloader.php';
Autoloader::run();

$activate_and_deactivate_action = [ Version_Control::class, 'on_activate_and_deactivate_plugin' ];

register_activation_hook( __FILE__, $activate_and_deactivate_action );
register_activation_hook( __FILE__, [ Admin_Bar::class, 'enable_elementor_inspector' ] );
register_deactivation_hook( __FILE__, $activate_and_deactivate_action );

// Bootstrap the plugin
new Bootstrap();
