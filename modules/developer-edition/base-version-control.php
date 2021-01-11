<?php
namespace ElementorBeta\Modules\DeveloperEdition;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Base_Version_Control {
	/**
	 * @return string|null
	 */
	public function get_current_version() {

		$plugin_config = $this->get_plugin_config();

		if ( ! $plugin_config['is_active'] ) {
			return null;
		}

		if ( defined( $plugin_config['version_const'] ) ) {
			return constant( $plugin_config['version_const'] );
		}

		$plugin_path = plugin_dir_path( $plugin_config['plugin_name'] );

		if ( is_readable( $plugin_path ) ) {
			return get_file_data( $plugin_path, [ 'Version' => 'Version' ], false )['Version'];
		}

		return null;
	}

	/**
	 * Return true if the version is stable or with the same channel
	 *
	 * Examples for valid version: 1.0.0, 1.0.0-dev1, 1.0.0.1, 1.0.0.1-dev2
	 *
	 * @param $is_valid
	 * @param $version
	 *
	 * @return bool
	 */
	protected function is_valid_rollback_version( $is_valid, $version ) {
		return (bool) preg_match( '/^\d+(\.\d+){2,3}(-dev\d*)?$/', $version );
	}

	/**
	 * @return array {
	 *  @type string $version_const
	 *  @type string $plugin_name
	 *  @type boolean $is_active
	 * }
	 */
	abstract protected function get_plugin_config();
}
