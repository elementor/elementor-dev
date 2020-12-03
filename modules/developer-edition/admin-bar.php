<?php
namespace ElementorDev\Modules\DeveloperEdition;

use ElementorDev\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Bar {
	/**
	 * Admin_Bar constructor.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 202 /* after elementor inspector */ );
		add_action( 'wp_enqueue_scripts', [ $this, 'print_style' ] );
	}

	/**
	 * When activate the plugin it automatically enable elementor inspector.
	 */
	public static function enable_elementor_inspector() {
		update_option( 'elementor_enable_inspector', 'enable' );
	}

	/**
	 * Register all the admin bar links.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		if ( is_admin() ) {
			return;
		}

		$wp_admin_bar->add_node( [
			'id' => 'elementor_inspector',
			'title' => __( 'Elementor Debugger', 'elementor-dev' ),
		] );

		$wp_admin_bar->add_node( [
			'id' => 'elementor_dev_secondary_report_issue',
			'title' => __( 'Report an issue', 'elementor-dev' ),
			'parent' => 'top-secondary',
			'href' => 'https://github.com/elementor/elementor/issues',
			'meta' => [
				'target' => '_blank',
			],
		] );

		$wp_admin_bar->add_menu( [
			'id' => 'elementor_inspector_elementor_dev',
			'parent' => 'elementor_inspector',
			'title' => __( 'Developer Edition', 'elementor-dev' ),
		] );

		if ( current_user_can( 'manage_options' ) ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_inspector_elementor_dev_system',
				'parent' => 'elementor_inspector_elementor_dev',
				'href' => self_admin_url( 'admin.php?page=elementor-system-info' ),
				'title' => '<strong>' . __( 'System info', 'elementor-dev' ) . '</strong>',
				'meta' => [
					'target' => '_blank',
				],
			] );
		}

		$wp_admin_bar->add_menu( [
			'id' => 'elementor_inspector_elementor_dev_report',
			'parent' => 'elementor_inspector_elementor_dev',
			'href' => 'https://github.com/elementor/elementor/issues',
			'title' => '<strong>' . __( 'Report an issue', 'elementor-dev' ) . '</strong>',
			'meta' => [
				'target' => '_blank',
			],
		] );

		$elementor_version = null;
		$elementor_path = plugin_dir_path( Bootstrap::ELEMENTOR_PLUGIN_NAME );

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$elementor_version = ELEMENTOR_VERSION;
		} elseif ( is_readable( $elementor_path ) ) {
			$elementor_version = get_file_data( $elementor_path, [ 'Version' => 'Version' ], false )['Version'];
		}

		if ( $elementor_version ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_inspector_elementor_dev_elementor_ver',
				'parent' => 'elementor_inspector_elementor_dev',
				'title' => __( 'Elementor', 'elementor-dev' ) . ' v' . $elementor_version,
			] );
		}
	}

	/**
	 * Print out the report issue icon.
	 */
	public function print_style() {
		if ( ! is_admin_bar_showing() || is_admin() ) {
			return;
		}

		wp_register_style( 'elementor-dev-admin-bar-inline', false, [], ELEMENTOR_DEV_VERSION );
		wp_enqueue_style( 'elementor-dev-admin-bar-inline' );

		wp_add_inline_style('elementor-dev-admin-bar-inline', '
			#wpadminbar #wp-admin-bar-elementor_dev_secondary_report_issue > .ab-item::before {
			    content: "\e813";
			    font-family: eicons;
			    top: 3px;
			    font-size: 18px;
			}
		');
	}
}
