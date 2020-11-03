<?php
namespace ElementorDev\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	protected static $instance = null;

	/**
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Bootstrap the plugin.
	 */
	public function bootstrap() {
		$this->register_autoloader();

		register_activation_hook( ELEMENTOR_DEV_FILE, [ $this, 'activate' ] );
		add_action( 'init', [ static::class, 'instance' ] );
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * Checks if the plugin can bootstrap, if not show a notice for the admin.
	 *
	 * @return bool
	 */
	public function can_bootstrap() {
		$is_elementor_exists = class_exists( 'Elementor\\Plugin' );

		if ( ! $is_elementor_exists ) {
			// Here will be the notice code.

//			require_once dirname( __FILE__ ) . '/notices.php';
//
//			$notices = new Notices();
//
//			add_action( 'admin_notices', [ $notices, 'elementor_not_installed' ] );
		}

		return $is_elementor_exists;
	}

	/**
	 * Run some actions when activate.
	 */
	public function activate() {
		//
	}

	public function init() {
		$this->modules_manager = new Modules_Manager();
	}

	/**
	 * Register autoloader.
	 */
	private function register_autoloader() {
		require_once __DIR__ . '/autoloader.php';

		Autoloader::run();
	}}
