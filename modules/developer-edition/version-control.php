<?php
/**
 * Inspired by: Woocommerce Beta Tester (https://github.com/woocommerce/woocommerce-beta-tester).
 */
namespace ElementorDev\Modules\DeveloperEdition;

use ElementorDev\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Version_Control {

	/**
	 * WordPress info url for the elementor plugin.
	 */
	const WP_ORG_ELEMENTOR_INFO_ENDPOINT = 'https://api.wordpress.org/plugins/info/1.0/elementor.json';

	/**
	 * Version_Control constructor.
	 */
	public function __construct() {
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'pre_set_site_transient_update_plugins' ] );
	}

	/**
	 * Holds the transient key of the versions data that returns from wp.org.
	 *
	 * @return string
	 */
	public static function get_wp_org_data_transient_key() {
		static $key;

		if ( ! $key ) {
			$key = md5( 'elementor_dev_wp_org_data' );
		}

		return $key;
	}

	/**
	 * Will be execute when the plugin is on activate or deactivate mode.
	 */
	public static function on_activate_and_deactivate_plugin() {
		// Force recheck for new plugin versions
		delete_site_transient( 'update_plugins' );
	}

	/**
	 * Checks if the elementor should updated or not based on the latest dev tag release.
	 *
	 * @param $transient
	 *
	 * @return object
	 */
	public function pre_set_site_transient_update_plugins( $transient ) {
		$current_version = $this->get_elementor_plugin_data()['Version'];
		$latest_dev_release = $this->get_latest_dev_release();

		if ( ! $latest_dev_release ) {
			return $transient;
		}

		$should_update = version_compare( $latest_dev_release, $current_version, '>' );

		if ( ! $should_update ) {
			return $transient;
		}

		// Populate response data.
		if ( ! isset( $transient->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ] ) ) {
			$transient->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ] = (object) [
				'plugin' => Bootstrap::ELEMENTOR_PLUGIN_NAME,
				'slug' => basename( Bootstrap::ELEMENTOR_PLUGIN_NAME, '.php' ),
				'url' => 'https://elementor.com/',
			];
		}

		$download_url = $this->get_download_url( $latest_dev_release );

		$transient->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ]->new_version = $latest_dev_release;
		$transient->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ]->zip_url = $download_url;
		$transient->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ]->package = $download_url;

		return $transient;
	}

	/**
	 * Returns the latest dev tag release.
	 *
	 * @return string|null
	 */
	public function get_latest_dev_release() {
		return $this->get_latest_release_by_channel( 'dev' );
	}

	/**
	 * Returns the latest stable tag release.
	 *
	 * @return string|null
	 */
	public function get_latest_stable_release() {
		return $this->get_latest_release_by_channel( 'stable' );
	}

	/**
	 * @param $channel
	 *
	 * @return int|string|null
	 */
	private function get_latest_release_by_channel( $channel ) {
		$tagged_version = null;

		$data = $this->get_wp_org_data();

		if ( ! $data ) {
			return null;
		}

		$regex = "/^\d+(\.\d+){2,3}-{$channel}\d*$/";

		if ( 'stable' === $channel ) {
			$regex = '/^\d+(\.\d+){2,3}$/';
		}

		foreach ( $data as $version => $download_url ) {
			if ( 'trunk' === $version ) {
				continue;
			}

			if ( 0 === preg_match_all( $regex, $version ) ) {
				continue;
			}

			$tagged_version = $version;
		}

		return $tagged_version;
	}

	/**
	 * Get Data from wp.org API.
	 *
	 * @return array
	 */
	private function get_wp_org_data() {
		$data = get_site_transient( static::get_wp_org_data_transient_key() );

		if ( ! empty( $data ) ) {
			return $data;
		}

		$data = wp_remote_get( self::WP_ORG_ELEMENTOR_INFO_ENDPOINT );

		if ( 200 !== (int) wp_remote_retrieve_response_code( $data ) ) {
			return [];
		}

		$data = json_decode( $data['body'], true )['versions'];

		// The versions that returns from the WordPress API are in a wrong order.
		// so before caching it, the function below reorders the versions from earlier version to the latest.
		uasort( $data, function ( $ver_a, $ver_b ) {
			if ( version_compare( $ver_a, $ver_b, '<' ) ) {
				return -1;
			}

			return 1;
		} );

		// Store cache for 6 hours.
		set_site_transient( static::get_wp_org_data_transient_key(), $data, HOUR_IN_SECONDS * 6 );

		return $data;
	}

	/**
	 * Get plugin download URL.
	 *
	 * @param string $version The version.
	 *
	 * @return string
	 */
	private function get_download_url( $version ) {
		$data = $this->get_wp_org_data();

		if ( empty( $data[ $version ] ) ) {
			return false;
		}

		return $data[ $version ];
	}

	/**
	 * Get Plugin data.
	 *
	 * @return array
	 */
	protected function get_elementor_plugin_data() {
		return get_plugin_data( WP_PLUGIN_DIR . '/' . Bootstrap::ELEMENTOR_PLUGIN_NAME );
	}
}
