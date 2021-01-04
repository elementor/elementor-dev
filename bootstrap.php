<?php
namespace ElementorBeta;

use ElementorBeta\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bootstrap {
	const ELEMENTOR_PLUGIN_NAME = 'elementor/elementor.php';
	const ELEMENTOR_PRO_PLUGIN_NAME = 'elementor-pro/elementor-pro.php';

	/**
	 * Bootstrap constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'elementor-beta' );

		if ( ! $this->is_elementor_class_exists() ) {
			add_action( 'admin_notices', [ $this, 'notice_elementor_class_not_exists' ] );

			return;
		}

		// initiate the plugin.
		Plugin::instance();
	}

	/**
	 * Notice to admin that elementor class is not exists.
	 */
	public function notice_elementor_class_not_exists() {
		if ( $this->is_install_screen() ) {
			return;
		}

		if ( ! $this->is_elementor_installed() && current_user_can( 'install_plugins' ) ) {
			require __DIR__ . '/views/admin-notices/elementor-not-installed.php';
		} elseif ( ! $this->is_elementor_active() && current_user_can( 'activate_plugin', self::ELEMENTOR_PLUGIN_NAME ) ) {
			require __DIR__ . '/views/admin-notices/elementor-not-active.php';
		}
	}

	/**
	 * Get all the plugins.
	 *
	 * This method is mostly for unit tests (mock this method to demonstrate a case that elementor is not installed).
	 *
	 * @return array[]
	 */
	protected function get_plugins() {
		return get_plugins();
	}

	/**
	 * Checks if elementor is active.
	 *
	 * This method is protected and not private mostly for unit tests (mock this method to demonstrate a case that elementor is not active).
	 *
	 * @return bool
	 */
	protected function is_elementor_active() {
		return is_plugin_active( self::ELEMENTOR_PLUGIN_NAME );
	}

	/**
	 * Checks if elementor class exists.
	 * this is an early check before it can check if the plugin installed or active.
	 *
	 * @return bool
	 */
	private function is_elementor_class_exists() {
		return class_exists( 'Elementor\\Plugin' );
	}

	/**
	 * Checks if elementor is installed.
	 *
	 * @return bool
	 */
	private function is_elementor_installed() {
		$installed_plugins = $this->get_plugins();

		return isset( $installed_plugins[ self::ELEMENTOR_PLUGIN_NAME ] );
	}

	/**
	 * Checks if is in install page.
	 *
	 * @return bool
	 */
	private function is_install_screen() {
		$screen = get_current_screen();

		return isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id;
	}
}
