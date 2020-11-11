<?php
namespace ElementorDev\Modules\DeveloperEdition;

use \ElementorDev\Core\Base\Module as BaseModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {
	/**
	 * Module constructor.
	 */
	public function __construct() {
		new Version_Control();
		new Admin_Bar();
	}
}
