<?php
namespace ElementorBeta\Tests;

use ElementorBeta\Bootstrap;
use ElementorBeta\Tests\Phpunit\Base_Test;
use ElementorBeta\Modules\DeveloperEdition\Core_Version_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Core_Version_Control extends Base_Test {
	/**
	 * @var Core_Version_Control
	 */
	protected $version_control;

	public function setUp() {
		$this->version_control = $this->getMockBuilder( Core_Version_Control::class )
			->setMethods( [ 'get_current_version' ] )
			->getMock();

		$versions = [
			'2.11.0',
			'2.12.0-dev1',
			'3.0.0',
			'3.1.0-dev1',
			'3.1.0-dev2',
			'3.2.0',
		];

		set_site_transient(
			Core_Version_Control::get_wp_org_data_transient_key(),
			array_reduce(
				$versions,
				function ( $current, $value ) {
					return array_merge( $current, [ $value => 'https://test-url.com/' . $value ] );
				},
				[]
			)
		);
	}

	/** @dataProvider version_that_should_have_updates_data_provider */
	public function test_pre_set_site_transient_update_plugins__should_return_updates( $version, $expected_version, $description ) {
		// Arrange
		$this->version_control->method( 'get_current_version' )->willReturn( $version );

		// Act
		$result = $this->version_control->pre_set_site_transient_update_plugins( (object) [ 'response' => [] ] );

		// Assert
		$this->assertArrayHasKey( Bootstrap::ELEMENTOR_PLUGIN_NAME, $result->response );

		$elementor_response = $result->response[ Bootstrap::ELEMENTOR_PLUGIN_NAME ];

		$this->assertEquals( $expected_version, $elementor_response->new_version, $description );
		$this->assertEquals( Bootstrap::ELEMENTOR_PLUGIN_NAME, $elementor_response->plugin );
		$this->assertEquals( 'https://test-url.com/' . $expected_version, $elementor_response->zip_url );
		$this->assertEquals( 'https://test-url.com/' . $expected_version, $elementor_response->package );
	}

	public function version_that_should_have_updates_data_provider() {
		return [
			[ '3.1.0-dev1', '3.1.0-dev2', 'Same patch version, lower dev' ],
			[ '3.0.0', '3.1.0-dev2', 'Stable version lower than dev' ],
		];
	}

	/** @dataProvider version_that_should_not_have_updates_data_provider */
	public function test_pre_set_site_transient_update_plugins__should_not_return_updates( $version, $description ) {
		// Arrange
		$this->version_control->method( 'get_current_version' )->willReturn( $version );

		// Act
		$result = $this->version_control->pre_set_site_transient_update_plugins( (object) [ 'response' => [] ] );

		// Assert
		$this->assertArrayNotHasKey( Bootstrap::ELEMENTOR_PLUGIN_NAME, $result->response, $description );
	}

	public function version_that_should_not_have_updates_data_provider() {
		return [
			[ '3.1.0', 'Same version, stable vs dev' ],
			[ '3.2.0', 'Bigger stable version, Lower dev' ],
			[ '3.1.0-dev3', 'Bigger dev version, Lower dev' ],
			[ '3.1.1-dev1', 'Bigger stable version, Lower dev' ],
		];
	}

	public function test_get_latest_stable_release() {
		$result = $this->version_control->get_latest_stable_release();

		$this->assertEquals( '3.2.0', $result );
	}

	/** @dataProvider is_valid_rollback_version_data_provider */
	public function test_is_valid_rollback_version( $version, $expect_valid ) {
		$result = apply_filters( 'elementor/settings/tools/rollback/is_valid_rollback_version', false, $version );

		$this->assertEquals( $expect_valid, $result );
	}

	public function is_valid_rollback_version_data_provider() {
		return [
			[ '3.0.0', true ],
			[ '3.1.0', true ],
			[ '20.21.21', true ],
			[ '3.1.0-dev', true ],
			[ '3.1.0.1-dev', true ],
			[ '3.1.0.1-dev1', true ],
			[ '22.22.22-dev', true ],
			[ '22.22.22-dev1', true ],
			[ '3.1.0-dev2', true ],
			[ '3.1.0-beta', false ],
			[ '3.1.0.1-beta', false ],
			[ '3.1.0.1-beta1', false ],
			[ '3.1.0-rc', false ],
			[ '3.1.0-beta1', false ],
			[ '3.1.0-rc3', false ],
		];
	}
}
