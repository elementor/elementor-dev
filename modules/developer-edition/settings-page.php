<?php
namespace ElementorDev\Modules\DeveloperEdition;

use Elementor\Plugin;
use Elementor\Settings;
use ElementorDev\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Settings_Page {
	/**
	 * The page id
	 */
	const PAGE_ID = 'elementor_dev_settings';

	/**
	 * Init all the settings that should be saved
	 */
	public function settings_init() {
		add_settings_section(
			'elementor-dev-developer-edition-update',
			__( 'Elementor Developer Edition', 'elementor-dev' ),
			null,
			static::PAGE_ID
		);

		add_settings_field(
			'elementor-dev-developer-edition-update-child',
			__( 'Get updates', 'elementor-dev' ),
			function () {
				$this->load_view( 'settings-page-get-updates.php' );
			},
			static::PAGE_ID,
			'elementor-dev-developer-edition-update'
		);

		if ( current_user_can( 'update_plugins' ) ) {
			add_settings_section(
				'elementor-dev-developer-edition-auto-update',
				__( 'Auto Plugin Updates', 'elementor-dev' ),
				null,
				static::PAGE_ID
			);

			register_setting( Module::SETTINGS_KEY, 'elementor_dev_auto_update', [
				'sanitize_callback' => [ $this, 'update_auto_update' ],
			] );

			add_settings_field(
				'elementor-dev-developer-edition-auto-update-field',
				__( 'Auto update Elementor', 'elementor-dev' ),
				function () {
					$this->load_view( 'settings-page-auto-update.php' );
				},
				static::PAGE_ID,
				'elementor-dev-developer-edition-auto-update'
			);
		}

		if ( 'yes' !== get_option( 'elementor_allow_tracking', 'no' ) ) {
			add_settings_section(
				'elementor-dev-developer-edition-improve',
				__( 'Improve Elementor', 'elementor-dev' ),
				null,
				static::PAGE_ID
			);

			register_setting( Module::SETTINGS_KEY, 'elementor_allow_tracking' );

			add_settings_field(
				'elementor-dev-developer-edition-improve-field',
				__( 'Usage Data Sharing', 'elementor-dev' ),
				function () {
					$this->load_view( 'settings-page-improve-elementor.php' );
				},
				static::PAGE_ID,
				'elementor-dev-developer-edition-improve'
			);
		}
	}

	/**
	 * Adds developer edition into elementor menu.
	 */
	public function add_to_menus() {
		add_submenu_page(
			Settings::PAGE_ID,
			__( 'Elementor Developer Edition', 'elementor-dev' ),
			__( 'Developer Edition', 'elementor-dev' ),
			'install_plugins',
			static::PAGE_ID,
			function () {
				$this->settings_page_html();
			}
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
			__( 'See something that isn’t working properly? %s', 'elementor-dev' ),
			'<a href="https://github.com/elementor/elementor/issues" target="_blank">' . __( 'Let us know.', 'elementor-dev' ) . '</a>'
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
				esc_html__( 'Settings', 'elementor-dev' )
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
	public function add_elementor_dev_modal_template() {
		if ( ! $this->is_elementor_dev_settings_page() ) {
			return;
		}

		Plugin::$instance->common->add_template( __DIR__ . '/views/settings-page-get-updates-modal.php' );
	}

	/**
	 * Update auto update plugins option.
	 *
	 * @param $value
	 */
	public function update_auto_update( $value ) {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', [] );

		if ( 'yes' === $value ) {
			$auto_updates = array_unique( array_merge( $auto_updates, [ Bootstrap::ELEMENTOR_PLUGIN_NAME ] ) );
		} else {
			$auto_updates = array_filter( $auto_updates, function ( $plugin ) {
				return Bootstrap::ELEMENTOR_PLUGIN_NAME !== $plugin;
			} );
		}

		update_site_option( 'auto_update_plugins', $auto_updates );
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

		$plugin_name = plugin_basename( ELEMENTOR_DEV_FILE );

		add_action( 'admin_menu', [ $this, 'add_to_menus' ], 21 /* After elementor menu */ );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_head', [ $this, 'add_elementor_dev_modal_template' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );
		add_filter( "plugin_action_links_{$plugin_name}", [ $this, 'plugin_action_links' ] );
	}
}
