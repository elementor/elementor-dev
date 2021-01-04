<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use ElementorBeta\Bootstrap;
use ElementorPro\License\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Pro_Version_Control extends Base_Version_Control {
	/**
	 * @var boolean
	 */
	protected $is_plugin_active;

	/**
	 * @return array
	 */
	protected function get_plugin_config() {
		return [
			'plugin_name' => Bootstrap::ELEMENTOR_PRO_PLUGIN_NAME,
			'version_const' => 'ELEMENTOR_PRO_VERSION',
			'is_active' => $this->is_plugin_active,
		];
	}

	/**
	 * @param array $args
	 * @param       $url
	 *
	 * @return array
	 */
	private function add_dev_param_to_get_version_request( array $args, $url ) {
		if (
			API::STORE_URL !== $url ||
			! isset( $args['body']['edd_action'] )
			|| 'get_version' !== $args['body']['edd_action']
		) {
			return $args;
		}

		$args['body']['dev'] = '1';

		return $args;
	}

	/**
	 * Pro_Version_Control constructor.
	 *
	 * @param $is_plugin_active
	 */
	public function __construct( $is_plugin_active ) {
		$this->is_plugin_active = $is_plugin_active;

		if ( $this->is_plugin_active ) {
			add_filter( 'http_request_args', function ( array $args, $url ) {
				return $this->add_dev_param_to_get_version_request( $args, $url );
			}, 10, 2 );

			add_filter( 'elementor-pro/settings/tools/rollback/is_valid_rollback_version', function ( $is_valid, $version ) {
				return $this->is_valid_rollback_version( $is_valid, $version );
			}, 10, 2 );
		}
	}
}
