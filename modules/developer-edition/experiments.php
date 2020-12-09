<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use Elementor\Core\Experiments\Manager as ExperimentsManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Experiments {
	/**
	 * Enable some experiments based on some conditions
	 *
	 * @param ExperimentsManager $experiments_manager
	 * @param array              $experimental_data
	 */
	public function enable_experiments( ExperimentsManager $experiments_manager, array $experimental_data ) {
		if ( ! $this->is_beta_release_or_higher( $experimental_data ) ) {
			return;
		}

		$experiments_manager->set_feature_default_state( $experimental_data['name'], ExperimentsManager::STATE_ACTIVE );
	}

	/**
	 * Check if the experimental is in beta release or higher.
	 *x
	 * @param array $experimental_data
	 *
	 * @return bool
	 */
	private function is_beta_release_or_higher( array $experimental_data ) {
		$changeable_release_statues = [
			ExperimentsManager::RELEASE_STATUS_BETA,
			ExperimentsManager::RELEASE_STATUS_RC,
			ExperimentsManager::RELEASE_STATUS_STABLE,
		];

		return in_array( $experimental_data['release_status'], $changeable_release_statues, true );
	}

	/**
	 * Experiments constructor.
	 */
	public function __construct() {
		add_action( 'elementor/experiments/feature-registered', function ( ExperimentsManager $experiments_manager, array $experimental_data ) {
			$this->enable_experiments( $experiments_manager, $experimental_data );
		}, 10, 2 );
	}
}
