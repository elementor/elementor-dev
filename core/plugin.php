<?php
namespace ElementorBeta\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Plugin {
	/**
	 * Class instance.
	 *
	 * @var static
	 */
	protected static $instance = null;

	/**
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return static An instance of the class.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->modules_manager = new Modules_Manager();

		add_action( 'init', [ $this, 'init' ], -1 /* Should run BEFORE Elementor */ );
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->modules_manager->load_modules();
	}
}
