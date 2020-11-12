<?php
namespace ElementorDev\Tests;

use ElementorDev\Bootstrap;
use ElementorDev\Core\Plugin;
use ElementorDev\Tests\Phpunit\Base_Test;
use ElementorDev\Modules\DeveloperEdition\Auto_Update;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Auto_Update extends Base_Test {
	/**
	 * @var Auto_Update
	 */
	private $auto_update;

	public function setUp() {
		parent::setUp();

		$this->auto_update = new Auto_Update();
	}

	public function test_auto_update_elementor__should_return_true() {
		// Arrange
		update_option( 'elementor_dev_auto_update', 'yes' );
		$plugin = (object) [ 'plugin' => Bootstrap::ELEMENTOR_PLUGIN_NAME ];

		// Act
		$result = $this->auto_update->auto_update_elementor( false, $plugin );

		// Assert
		$this->assertTrue( $result );
	}

	public function test_auto_update_elementor__should_return_the_old_value_when_the_plugin_is_not_elementor() {
		// Arrange
		update_option( 'elementor_dev_auto_update', 'yes' );
		$plugin = (object) [ 'plugin' => 'test-plugin/test-plugin.php' ];

		$old_value = 'test';

		// Act
		$result = $this->auto_update->auto_update_elementor( $old_value, $plugin );

		// Assert
		$this->assertEquals( $old_value, $result );
	}

	public function test_auto_update_elementor__should_return_the_old_value_when_elementor_dev_auto_update_value_is_no() {
		// Arrange
		update_option( 'elementor_dev_auto_update', 'no' );
		$plugin = (object) [ 'plugin' => Bootstrap::ELEMENTOR_PLUGIN_NAME ];

		$old_value = 'test';

		// Act
		$result = $this->auto_update->auto_update_elementor( $old_value, $plugin );

		// Assert
		$this->assertEquals( $old_value, $result );
	}
}
