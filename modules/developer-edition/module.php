<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use ElementorBeta\Bootstrap;
use \ElementorBeta\Core\Base\Module as BaseModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {
	const SETTINGS_KEY = 'elementor_beta_developer_edition';

	/**
	 * @var Core_Version_Control
	 */
	public $core_version_control;

	/**
	 * @var Pro_Version_Control
	 */
	public $pro_version_control;

	/**
	 * Will be execute when the plugin is on activate or deactivate mode.
	 */
	public static function on_activate_and_deactivate_plugin() {
		// Force recheck for new plugin versions
		delete_site_transient( 'update_plugins' );

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			// Force recalculate rollback versions in elementor.
			delete_transient( 'elementor_rollback_versions_' . ELEMENTOR_VERSION );
		}

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			// Force recalculate rollback versions in elementor pro.
			delete_transient( 'elementor_pro_rollback_versions_' . ELEMENTOR_PRO_VERSION );
		}
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->core_version_control = new Core_Version_Control();
		$this->pro_version_control = new Pro_Version_Control( class_exists( 'ElementorPro\\Plugin' ) );

		new Settings_Page();
		new Admin_Bar();
		new UI();
		new Experiments();
	}
}
