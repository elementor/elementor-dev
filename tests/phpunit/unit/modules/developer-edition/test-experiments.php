<?php
namespace ElementorBeta\Tests;

use Elementor\Core\Experiments\Manager;
use ElementorBeta\Tests\Phpunit\Base_Test;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Experiments extends Base_Test {
	public function test_enable_experiments() {
		// Arrange
		$data = [
			'name' => 'test feature',
			'state' => Manager::STATE_DEFAULT,
			'release_status' => Manager::RELEASE_STATUS_BETA,
			'default' => Manager::STATE_INACTIVE,
		];

		$manager = new Manager();
		$manager->add_feature( $data );

		// Act
		do_action( 'elementor/experiments/feature-registered', $manager, $data );

		// Assert
		$features = $manager->get_features();

		$this->assertArrayHasKey( 'test feature', $features );
		$this->assertEquals( Manager::STATE_ACTIVE, $features['test feature']['default'] );
	}

	public function test_enable_experiments__should_not_active_when_release_status_is_lower_then_beta() {
		// Arrange
		$data = [
			'name' => 'test feature',
			'state' => Manager::STATE_DEFAULT,
			'release_status' => Manager::RELEASE_STATUS_ALPHA,
			'default' => Manager::STATE_INACTIVE,
		];

		$manager = new Manager();
		$manager->add_feature( $data );

		// Act
		do_action( 'elementor/experiments/feature-registered', $manager, $data );

		// Assert
		$features = $manager->get_features();

		$this->assertArrayHasKey( 'test feature', $features );
		$this->assertEquals( Manager::STATE_INACTIVE, $features['test feature']['default'] );
	}
}
