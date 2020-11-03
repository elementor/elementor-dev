<?php
namespace ElementorDev\Tests\Phpunit;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @mixin Base_Test
 */
trait Auth_Trait {
	/**
	 * @return \WP_User
	 */
	public function act_as_admin() {
		return $this->act_as( 'administrator' );
	}

	/**
	 * @return \WP_User
	 */
	public function act_as_editor() {
		return $this->act_as( 'editor' );
	}

	/**
	 * @param $role
	 *
	 * @return \WP_User
	 */
	public function act_as( $role ) {
		$user = $this->factory()->user->create_and_get( [ 'role' => $role ] );

		wp_set_current_user( $user->ID );

		return $user;
	}
}
