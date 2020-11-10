<?php
namespace ElementorDev\Modules\DeveloperEdition;

use \ElementorDev\Core\Base\Module as BaseModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {
	const SETTINGS_KEY = 'elementor_dev_developer_edition';

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
		new Auto_Update();
		new Admin_Bar();
		new UI();
	}
}
