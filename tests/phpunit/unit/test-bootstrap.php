<?php
namespace ElementorBeta\Tests;

use ElementorBeta\Bootstrap;
use ElementorBeta\Tests\Phpunit\Base_Test;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Bootstrap extends Base_Test {
	public function test_notice_elementor_class_not_exists__should_call_elementor_not_installed() {
		// Arrange
		$this->act_as_admin();
		$bootstrap = $this->getMockBuilder( Bootstrap::class )
			->setMethods( [ 'get_plugins' ] )
			->getMock();

		// Act
		ob_start();
		$bootstrap->notice_elementor_class_not_exists();

		$content = ob_get_clean();

		// Assert
		$this->assertRegexp( '/id="elementor-not-installed"/', $content );
	}

	public function test_notice_elementor_class_not_exists__should_call_elementor_not_active() {
		// Arrange
		$this->act_as_admin();
		$bootstrap = $this->getMockBuilder( Bootstrap::class )
			->setMethods( [ 'is_elementor_active' ] )
			->getMock();

		// Act
		ob_start();
		$bootstrap->notice_elementor_class_not_exists();

		$content = ob_get_clean();

		// Assert
		$this->assertRegexp( '/id="elementor-not-active"/', $content );
	}

	public function test_notice_elementor_class_not_exists__should_not_print_nothing_in_install_screen() {
		// Arrange
		$this->act_as_admin();

		$screen = \WP_Screen::get( 'update' );
		$screen->parent_file = 'plugins.php';

		set_current_screen( $screen );

		$bootstrap = $this->getMockBuilder( Bootstrap::class )
			->setMethods( [ 'get_plugins' ] )
			->getMock();

		// Act
		ob_start();
		$bootstrap->notice_elementor_class_not_exists();

		$content = ob_get_clean();

		// Assert
		$this->assertNotRegExp( '/id="elementor-not-installed"/', $content );
		$this->assertNotRegExp( '/id="elementor-not-active"/', $content );
	}

	public function test_notice_elementor_class_not_exists__should_not_print_elementor_not_installed_if_user_cant_install_plugins() {
		// Arrange
		$this->act_as_editor();
		$bootstrap = $this->getMockBuilder( Bootstrap::class )
			->setMethods( [ 'get_plugins' ] )
			->getMock();

		// Act
		ob_start();
		$bootstrap->notice_elementor_class_not_exists();

		$content = ob_get_clean();

		// Assert
		$this->assertNotRegExp( '/id="elementor-not-installed"/', $content );
	}

	public function test_notice_elementor_class_not_exists__should_not_print_elementor_not_active_if_user_cant_active_plugins() {
		// Arrange
		$this->act_as_editor();
		$bootstrap = $this->getMockBuilder( Bootstrap::class )
			->setMethods( [ 'is_elementor_active' ] )
			->getMock();

		// Act
		ob_start();
		$bootstrap->notice_elementor_class_not_exists();

		$content = ob_get_clean();

		// Assert
		$this->assertNotRegExp( '/id="elementor-not-active"/', $content );
	}
}
