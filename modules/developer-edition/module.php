<?php
namespace ElementorBeta\Modules\DeveloperEdition;

use \ElementorBeta\Core\Base\Module as BaseModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {
	const SETTINGS_KEY = 'elementor_beta_developer_edition';

	/**
	 * @var Version_Control
	 */
	public $version_control;

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->version_control = new Version_Control();
		new Settings_Page();
		new Admin_Bar();
		new UI();
	}
}
