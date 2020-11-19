<?php
namespace ElementorDev\Tests;

use ElementorDev\Tests\Phpunit\Base_Test;
use ElementorDev\Modules\DeveloperEdition\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_UI extends Base_Test {
	public function test_enqueue_scripts__should_enqueue_on_elementor_editor() {
		// Arrange
		global $wp_styles;
		global $wp_scripts;

		new UI();

		// Act
		do_action( 'elementor/editor/after_enqueue_scripts' );

		// Assert
		$this->assertTrue( in_array( 'elementor-dev-developer-edition', $wp_styles->queue, true ) );
		$this->assertTrue( in_array( 'elementor-dev-developer-edition', $wp_scripts->queue, true ) );
	}

	public function test_enqueue_styles__should_enqueue_styles_on_admin() {
		// Arrange
		global $wp_styles;

		new UI();

		// Act
		do_action( 'admin_enqueue_scripts' );

		// Assert
		$this->assertTrue( in_array( 'elementor-dev-developer-edition', $wp_styles->queue, true ) );
	}

	public function test_add_body_class() {
		// Arrange
		new UI();

		// Act
		$body_classes = apply_filters( 'body_class', [] );

		// Assert
		$this->assertTrue( in_array( 'elementor-dev', $body_classes, true ) );
	}

	public function test_add_admin_body_class() {
		// Arrange
		new UI();

		// Act
		$admin_body_classes = apply_filters( 'admin_body_class', '' );

		// Assert
		$admin_body_classes = explode( ' ', $admin_body_classes );

		$this->assertTrue( in_array( 'elementor-dev', $admin_body_classes, true ) );
	}
}
