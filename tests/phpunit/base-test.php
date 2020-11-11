<?php
namespace ElementorDev\Tests\Phpunit;

use WP_UnitTestCase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Base_Test extends WP_UnitTestCase {
	use Auth_Trait;

	public function setUp() {
		parent::setUp();

		global $wp_styles;

		set_current_screen( 'dashboard' );
		$wp_styles = new \WP_Styles();
	}
}
