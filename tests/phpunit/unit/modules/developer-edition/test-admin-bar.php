<?php
namespace ElementorDev\Tests;

use ElementorDev\Tests\Phpunit\Base_Test;
use ElementorDev\Modules\DeveloperEdition\Admin_Bar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Test_Admin_Bar extends Base_Test {
	public static function setUpBeforeClass() {
		require_once ABSPATH . '/wp-includes/class-wp-admin-bar.php';

		return parent::setUpBeforeClass();
	}

	public function test_add_menu_to_admin_bar() {
		// Arrange
		$this->act_as_admin();
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'front' );
		set_current_screen( $screen );

		$wp_admin_bar = new \WP_Admin_Bar();

		// Act
		do_action( 'admin_bar_menu', $wp_admin_bar );

		// Assert
		$expected_admin_bar_items = [
			[
				'id' => 'elementor_inspector',
				'parent' => false,
			],
			[
				'id' => 'elementor_dev_secondary_report_issue',
				'parent' => 'top-secondary',
			],
			[
				'id' => 'elementor_inspector_elementor_dev',
				'parent' => 'elementor_inspector',
			],
			[
				'id' => 'elementor_inspector_elementor_dev_system',
				'parent' => 'elementor_inspector_elementor_dev',
			],
			[
				'id' => 'elementor_inspector_elementor_dev_report',
				'parent' => 'elementor_inspector_elementor_dev',
			],
			[
				'id' => 'elementor_inspector_elementor_dev_elementor_ver',
				'parent' => 'elementor_inspector_elementor_dev',
			],
		];

		$admin_bar_items = $wp_admin_bar->get_nodes();

		$this->assertTrue( is_array( $admin_bar_items ) );

		foreach ( $expected_admin_bar_items as $item ) {
			$this->assertArrayHasKey( $item['id'], $admin_bar_items );
			$this->assertEquals( $item['parent'], $admin_bar_items[ $item['id'] ]->parent );
		}
	}

	public function test_add_menu_to_admin_bar__should_not_load_elementor_dev_admin_bar_when_in_admin_screen() {
		// Arrange
		$this->act_as_admin();
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'admin' );
		set_current_screen( $screen );

		$wp_admin_bar = new \WP_Admin_Bar();

		// Act
		do_action( 'admin_bar_menu', $wp_admin_bar );

		// Assert
		$this->assertEmpty( $wp_admin_bar->get_nodes() );
	}

	public function test_add_menu_to_admin_bar__should_not_show_system_info_to_users_without_permissions() {
		// Arrange
		$this->act_as_editor();
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'front' );
		set_current_screen( $screen );

		$wp_admin_bar = new \WP_Admin_Bar();

		// Act
		do_action( 'admin_bar_menu', $wp_admin_bar );

		// Assert
		$admin_bar_items = $wp_admin_bar->get_nodes();

		$this->assertTrue( is_array( $admin_bar_items ) );

		$this->assertArrayNotHasKey( 'elementor_inspector_elementor_dev_system', $admin_bar_items );
	}

	public function test_print_style() {
		// Arrange
		global $wp_styles;

		show_admin_bar( true );
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'front' );
		set_current_screen( $screen );

		// Act
		do_action( 'wp_enqueue_scripts' );

		// Assert
		$this->assertTrue( in_array( 'elementor-dev-admin-bar-inline', $wp_styles->queue, true ) );
	}

	public function test_print_style__should_not_print_style_when_admin_bar_is_not_shown() {
		// Arrange
		global $wp_styles;

		show_admin_bar( false );
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'front' );
		set_current_screen( $screen );

		// Act
		do_action( 'wp_enqueue_scripts' );

		// Assert
		$this->assertFalse( in_array( 'elementor-dev-admin-bar-inline', $wp_styles->queue, true ) );
	}

	public function test_print_style__should_not_print_style_when_screen_is_admin_screen() {
		// Arrange
		global $wp_styles;

		show_admin_bar( true );
		$admin_bar = new Admin_Bar();

		$screen = \WP_Screen::get( 'admin' );
		set_current_screen( $screen );

		// Act
		do_action( 'wp_enqueue_scripts' );

		// Assert
		$this->assertFalse( in_array( 'elementor-dev-admin-bar-inline', $wp_styles->queue, true ) );
	}
}
