<?php
namespace ElementorDev\Modules\DeveloperEdition;

use ElementorDev\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Auto_Update {

	/**
	 * Auto_Update constructor.
	 */
	public function __construct() {
		add_filter( 'auto_update_plugin', [ $this, 'auto_update_elementor' ], 10, 2 );
	}

	/**
	 * @param $should_update
	 * @param $plugin
	 *
	 * @return bool
	 */
	public function auto_update_elementor( $should_update, $plugin ) {
		if ( Bootstrap::ELEMENTOR_PLUGIN_NAME !== $plugin->plugin || 'yes' !== get_option( 'elementor_dev_auto_update', 'no' ) ) {
			return $should_update;
		}

		return true;
	}
}
