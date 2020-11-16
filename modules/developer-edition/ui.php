<?php
namespace ElementorDev\Modules\DeveloperEdition;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class UI {
	/**
	 * Enqueue scrips.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'elementor-dev-developer-edition',
			ELEMENTOR_DEV_URL . 'modules/developer-edition/assets/js/developer-edition.js',
			[],
			ELEMENTOR_DEV_VERSION,
			true
		);

		$ui_theme = 'auto';
		$elementor_preferences = get_user_meta( get_current_user_id(), 'elementor_preferences', true );

		if ( isset( $elementor_preferences['ui_theme'] ) ) {
			$ui_theme = $elementor_preferences['ui_theme'];
		}

		wp_add_inline_script( 'elementor-dev-developer-edition', "const elementorDevUiTheme = '{$ui_theme}';" );
	}

	/**
	 * Enqueue styles
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'elementor-dev-developer-edition',
			ELEMENTOR_DEV_URL . 'modules/developer-edition/assets/css/developer-edition.css',
			[],
			ELEMENTOR_DEV_VERSION
		);
	}

	/**
	 * Add Elementor dev class into frontend body.
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function add_body_class( array $classes ) {
		$classes[] = 'elementor-dev';

		return $classes;
	}

	/**
	 * Add Elementor dev class into admin body.
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	public function add_admin_body_class( $classes ) {
		return $classes . ' elementor-dev';
	}

	/**
	 * UI constructor.
	 */
	public function __construct() {
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
		add_filter( 'admin_body_class', [ $this, 'add_admin_body_class' ] );
	}
}
