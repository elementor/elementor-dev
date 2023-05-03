<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use Elementor\Plugin;
use Elementor\Settings;
use ElementorBeta\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Settings_Page {
	/**
	 * The page id
	 */
	const PAGE_ID = 'elementor_beta_settings';

	/**
	 * Init all the settings that should be saved
	 */
	public function settings_init() {
		add_settings_section(
			'elementor-beta-developer-edition-update',
			__( 'Elementor Developer Edition', 'elementor-beta' ),
			null,
			static::PAGE_ID
		);

		add_settings_field(
			'elementor-beta-developer-edition-update-child',
			__( 'Get updates', 'elementor-beta' ),
			function () {
				$this->load_view( 'settings-page-get-updates.php' );
			},
			static::PAGE_ID,
			'elementor-beta-developer-edition-update'
		);

		add_settings_field(
			'elementor-beta-developer-edition-update-child-core',
			__( 'Elementor', 'elementor-beta' ),
			function () {
				$this->load_view( 'settings-page-get-updates-core.php' );
			},
			static::PAGE_ID,
			'elementor-beta-developer-edition-update'
		);

		if ( is_plugin_active( Bootstrap::ELEMENTOR_PRO_PLUGIN_NAME ) ) {
			add_settings_field(
				'elementor-beta-developer-edition-update-child-pro',
				__( 'Elementor Pro', 'elementor-beta' ),
				function () {
					$this->load_view( 'settings-page-get-updates-pro.php' );
				},
				static::PAGE_ID,
				'elementor-beta-developer-edition-update'
			);
		}


		add_settings_field(
			'elementor-beta-developer-edition-update-child-description',
			'',
			function () {
				$this->load_view( 'settings-page-get-updates-description.php' );
			},
			static::PAGE_ID,
			'elementor-beta-developer-edition-update'
		);

		if ( current_user_can( 'update_plugins' ) ) {
			add_settings_section(
				'elementor-beta-developer-edition-auto-update',
				__( 'Auto Plugin Updates', 'elementor-beta' ),
				null,
				static::PAGE_ID
			);

			register_setting( Module::SETTINGS_KEY, 'elementor_beta_auto_update', [
				'sanitize_callback' => [ $this, 'sanitize_update_auto_update' ],
			] );

			add_settings_field(
				'elementor-beta-developer-edition-auto-update-field',
				__( 'Auto update Elementor', 'elementor-beta' ),
				function () {
					$this->load_view( 'settings-page-auto-update.php' );
				},
				static::PAGE_ID,
				'elementor-beta-developer-edition-auto-update'
			);
		}

		if ( 'yes' !== get_option( 'elementor_allow_tracking', 'no' ) ) {
			add_settings_section(
				'elementor-beta-developer-edition-improve',
				__( 'Improve Elementor', 'elementor-beta' ),
				null,
				static::PAGE_ID
			);

			register_setting( Module::SETTINGS_KEY, 'elementor_allow_tracking' );

			add_settings_field(
				'elementor-beta-developer-edition-improve-field',
				__( 'Usage Data Sharing', 'elementor-beta' ),
				function () {
					$this->load_view( 'settings-page-improve-elementor.php' );
				},
				static::PAGE_ID,
				'elementor-beta-developer-edition-improve'
			);
		}
	}

	/**
	 * Adds developer edition into elementor menu.
	 */
	public function add_to_menus() {
		add_submenu_page(
			Settings::PAGE_ID,
			__( 'Elementor Developer Edition', 'elementor-beta' ),
			__( 'Developer Edition', 'elementor-beta' ),
			'install_plugins',
			static::PAGE_ID,
			function () {
				$this->settings_page_html();
			},
			11 // After Elementor's sub menus - before Elementor's Upgrage sub menu item
		);
	}

	/**
	 * Change the admin footer text.
	 *
	 * @param $footer_text
	 *
	 * @return string
	 */
	public function admin_footer_text( $footer_text ) {
		if ( ! $this->is_elementor_dev_settings_page() ) {
			return $footer_text;
		}

		return sprintf(
			/* translators: %s: Link to issues report */
			__( 'See something that isn’t working properly? %s', 'elementor-beta' ),
			'<a href="https://github.com/elementor/elementor/issues" target="_blank">' . __( 'Let us know.', 'elementor-beta' ) . '</a>'
		);
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param   array $links Plugin Action links.
	 * @return  array
	 */
	public function plugin_action_links( $links ) {
		return array_merge( [
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'admin.php?page=' . static::PAGE_ID ) ),
				esc_html__( 'Settings', 'elementor-beta' )
			),
		], $links );
	}

	/**
	 * Enqueue settings scripts.
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_elementor_dev_settings_page() ) {
			return;
		}

		Plugin::$instance->admin->enqueue_beta_tester_scripts();
	}

	/**
	 * Load the get updates modal into elementor templates.
	 */
	public function add_elementor_beta_modal_template() {
		if ( ! $this->is_elementor_dev_settings_page() ) {
			return;
		}

		Plugin::$instance->common->add_template( __DIR__ . '/views/settings-page-get-updates-modal.php' );
	}

	/**
	 * Update auto update plugins option.
	 *
	 * @param $value
	 *
	 * @return false
	 */
	public function sanitize_update_auto_update( $value ) {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', [] );

		if ( 'yes' === $value ) {
			$auto_updates = array_unique( array_merge( $auto_updates, [ Bootstrap::ELEMENTOR_PLUGIN_NAME ] ) );
		} else {
			$auto_updates = array_filter( $auto_updates, function ( $plugin ) {
				return Bootstrap::ELEMENTOR_PLUGIN_NAME !== $plugin;
			} );
		}

		update_site_option( 'auto_update_plugins', $auto_updates );

		return false;
	}

	/**
	 * Render settings page html.
	 */
	private function settings_page_html() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// show error/update messages.
		settings_errors();

		$this->load_view( 'settings-page.php' );
	}

	/**
	 * Load a specific view base on the $name arg
	 *
	 * @param $name
	 */
	private function load_view( $name ) {
		require __DIR__ . '/views/' . $name;
	}

	/**
	 * Checks if the current screen is elementor dev settings.
	 *
	 * @return bool
	 */
	private function is_elementor_dev_settings_page() {
		$screen = get_current_screen();

		return $screen && Settings::PAGE_ID . '_page_' . static::PAGE_ID === $screen->id;
	}

	/**
	 * Settings_Page constructor.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$plugin_name = plugin_basename( ELEMENTOR_BETA_FILE );

		add_action( 'admin_menu', [ $this, 'add_to_menus' ], 206 /* After elementor tools sub menu */ );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_head', [ $this, 'add_elementor_beta_modal_template' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], 11 /* After elementor */ );
		add_filter( "plugin_action_links_{$plugin_name}", [ $this, 'plugin_action_links' ] );
	}
}
