<?php
namespace ElementorBeta\Core;

use ElementorBeta\Core\Base\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Modules_Manager {
	/**
	 * Registered modules.
	 *
	 * Holds the list of all the registered modules.
	 *
	 * @var array
	 */
	private $modules = [];

	public function load_modules() {
		$modules_namespace_prefix = $this->get_modules_namespace_prefix();

		foreach ( $this->get_modules_names() as $module_name ) {
			$class_name = str_replace(
				' ',
				'',
				ucwords(
					str_replace( '-', ' ', $module_name )
				)
			);

			/** @var Module $class_name */
			$class_name = $modules_namespace_prefix . '\\Modules\\' . $class_name . '\Module';

			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * Get modules names.
	 *
	 * Retrieve the modules names.
	 *
	 * @return string[] Modules names.
	 */
	public function get_modules_names() {
		return [
			'developer-edition',
		];
	}

	/**
	 * Get modules.
	 *
	 * Retrieve all the registered modules or a specific module.
	 *
	 * @param string $module_name Module name.
	 *
	 * @return null|Module|Module[] All the registered modules or a specific module.
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	/**
	 * Get modules namespace prefix.
	 *
	 * Retrieve the modules namespace prefix.
	 *
	 * @return string Modules namespace prefix.
	 */
	protected function get_modules_namespace_prefix() {
		return ELEMENTOR_BETA_NAMESPACE;
	}
}
