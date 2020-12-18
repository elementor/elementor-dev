<?php
namespace ElementorBeta\Core\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Module {
	/**
	 * Class instance.
	 *
	 * @var static
	 */
	protected static $instance = null;

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
	 * @return bool
	 */
	public static function is_active() {
		return true;
	}
}
