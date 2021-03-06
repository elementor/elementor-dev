<?php
namespace ElementorBeta\Tests;

use ElementorBeta\Bootstrap;
use ElementorBeta\Tests\Phpunit\Base_Test;
use ElementorBeta\Modules\DeveloperEdition\Settings_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Settings_Page extends Base_Test {
	public function test_sanitize_update_auto_update__should_include_elementor_when_yes_passed() {
		// Arrange
		$settings_page = new Settings_Page();

		// Act
		$settings_page->sanitize_update_auto_update( 'yes' );

		// Assert
		$this->assertTrue( in_array( Bootstrap::ELEMENTOR_PLUGIN_NAME, get_site_option( 'auto_update_plugins' ), true ) );
	}

	public function test_sanitize_update_auto_update__should_not_include_elementor_when_null_passed() {
		// Arrange
		$settings_page = new Settings_Page();

		// Act
		$settings_page->sanitize_update_auto_update( null );

		// Assert
		$this->assertFalse( in_array( Bootstrap::ELEMENTOR_PLUGIN_NAME, get_site_option( 'auto_update_plugins' ), true ) );
	}
}
