<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use ElementorBeta\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Bar {
	const REPORT_AN_ISSUE_URL = 'https://go.elementor.com/wp-dash-report-an-issue/';

	/**
	 * Admin_Bar constructor.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 202 /* after elementor inspector */ );
		add_action( 'wp_enqueue_scripts', [ $this, 'print_style' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'print_style' ] );
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
		// Always add "report an issue" link even at admin pages.
		$wp_admin_bar->add_node( [
			'id' => 'elementor_beta_secondary_report_issue',
			'title' => __( 'Report an issue', 'elementor-beta' ),
			'parent' => 'top-secondary',
			'href' => self::REPORT_AN_ISSUE_URL,
			'meta' => [
				'target' => '_blank',
			],
		] );

		if ( is_admin() ) {
			return;
		}

		$wp_admin_bar->add_node( [
			'id' => 'elementor_inspector',
			'title' => __( 'Elementor Debugger', 'elementor-beta' ),
		] );

		$wp_admin_bar->add_menu( [
			'id' => 'elementor_inspector_elementor_beta',
			'parent' => 'elementor_inspector',
			'title' => __( 'Developer Edition', 'elementor-beta' ),
		] );

		if ( current_user_can( 'manage_options' ) ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_inspector_elementor_beta_system',
				'parent' => 'elementor_inspector_elementor_beta',
				'href' => self_admin_url( 'admin.php?page=elementor-system-info' ),
				'title' => '<strong>' . __( 'System info', 'elementor-beta' ) . '</strong>',
				'meta' => [
					'target' => '_blank',
				],
			] );
		}

		$wp_admin_bar->add_menu( [
			'id' => 'elementor_inspector_elementor_beta_report',
			'parent' => 'elementor_inspector_elementor_beta',
			'href' => self::REPORT_AN_ISSUE_URL,
			'title' => '<strong>' . __( 'Report an issue', 'elementor-beta' ) . '</strong>',
			'meta' => [
				'target' => '_blank',
			],
		] );

		/** @var Module $module */
		$module = Plugin::instance()
			->modules_manager
			->get_modules( 'developer-edition' );

		$elementor_version = $module->core_version_control->get_current_version();
		$elementor_pro_version = $module->pro_version_control->get_current_version();

		if ( $elementor_version ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_inspector_elementor_beta_elementor_ver',
				'parent' => 'elementor_inspector_elementor_beta',
				'title' => __( 'Elementor', 'elementor-beta' ) . ' v' . $elementor_version,
			] );
		}

		if ( $elementor_pro_version ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_inspector_elementor_beta_elementor_pro_ver',
				'parent' => 'elementor_inspector_elementor_beta',
				'title' => __( 'Elementor Pro', 'elementor-beta' ) . ' v' . $elementor_pro_version,
			] );
		}
	}

	/**
	 * Print out the report issue icon.
	 */
	public function print_style() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		wp_register_style( 'elementor-beta-admin-bar-inline', false, [], ELEMENTOR_BETA_VERSION );
		wp_enqueue_style( 'elementor-beta-admin-bar-inline' );

		wp_add_inline_style('elementor-beta-admin-bar-inline', '
			#wpadminbar #wp-admin-bar-elementor_beta_secondary_report_issue > .ab-item::before {
			    content: "\e813";
			    font-family: eicons;
			    top: 3px;
			    font-size: 18px;
			}
		');
	}
}
