<?php
namespace ElementorDev\Tests\Phpunit\Core;

use ElementorDev\Core\Plugin;
use ElementorDev\Tests\Phpunit\Base_Test;

class Test_Plugin extends Base_Test {
	public function test_plugin_activated() {
		$this->assertTrue( is_plugin_active( PLUGIN_PATH ) );
	}

	public function test_getInstance() {
		$this->assertInstanceOf( Plugin::class, Plugin::instance() );
	}
}
