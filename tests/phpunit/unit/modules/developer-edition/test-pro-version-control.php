<?php
namespace ElementorBeta\Tests;

use ElementorPro\License\API;
use ElementorBeta\Tests\Phpunit\Base_Test;
use ElementorBeta\Modules\DeveloperEdition\Pro_Version_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Pro_Version_Control extends Base_Test {
	public function setUp() {
		parent::setUp();

		require_once __DIR__ . '/../../mocks/api.php';
	}

	public function test_add_dev_param_to_get_version_request() {
		// Arrange
		new Pro_Version_Control( true );

		// Act
		$result = apply_filters(
			'http_request_args',
			[
				'body' => [
					'edd_action' => 'get_version',
				],
			],
			API::STORE_URL
		);

		// Assert
		$this->assertArrayHasKey( 'dev', $result['body'] );
		$this->assertEquals( '1', $result['body']['dev'] );
	}

	public function test_add_dev_param_to_get_version_request__when_another_url_sent() {
		// Arrange
		new Pro_Version_Control( true );

		// Act
		$result = apply_filters(
			'http_request_args',
			[
				'body' => [
					'edd_action' => 'test',
				],
			],
			API::STORE_URL
		);

		// Assert
		$this->assertArrayNotHasKey( 'dev', $result['body'] );
	}

	public function test_add_dev_param_to_get_version_request__when_edd_action_is_not_get_version() {
		// Arrange
		new Pro_Version_Control( true );

		// Act
		$result = apply_filters(
			'http_request_args',
			[
				'body' => [
					'edd_action' => 'get_version',
				],
			],
			'https://test.com'
		);

		// Assert
		$this->assertArrayNotHasKey( 'dev', $result['body'] );
	}

	public function test_add_dev_param_to_get_version_request__when_elementor_pro_is_not_installed() {
		// Arrange
		new Pro_Version_Control( false );

		// Act
		$result = apply_filters(
			'http_request_args',
			[
				'body' => [
					'edd_action' => 'get_version',
				],
			],
			API::STORE_URL
		);

		// Assert
		$this->assertArrayNotHasKey( 'dev', $result['body'] );
	}


	/** @dataProvider is_valid_rollback_version_data_provider */
	public function test_is_valid_rollback_version( $version, $expect_valid ) {
		// Arrange
		new Pro_Version_Control( true );

		// Act
		$result = apply_filters( 'elementor-pro/settings/tools/rollback/is_valid_rollback_version', false, $version );

		// Assert
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
