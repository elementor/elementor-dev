<?php
namespace ElementorDev\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Module {
	/**
	 * Module instance.
	 *
	 * @var Module
	 */
	protected static $instance = null;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the module class is loaded or can be loaded.
	 *
	 * @return Module An instance of the class.
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
