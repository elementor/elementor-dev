<?php
$_tests_dir = getenv( 'WP_TESTS_UTILS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = __DIR__ . '/../../tmp/wordpress-tests-lib';
}

define( 'ELEMENTOR_DEVELOPER_EDITION_TESTS', true );

/**
 * change PLUGIN_FILE env in phpunit.xml
 */
define( 'PLUGIN_FILE', getenv( 'PLUGIN_FILE' ) );
define( 'PLUGIN_FOLDER', basename( dirname( dirname( __DIR__ ) ) ) );
define( 'PLUGIN_PATH', PLUGIN_FOLDER . '/' . PLUGIN_FILE );

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = [
	'active_plugins' => [ PLUGIN_PATH ],
	'template' => 'twentynineteen',
	'stylesheet' => 'twentynineteen',
];

require_once $_tests_dir . '/includes/functions.php';

tests_add_filter( 'muplugins_loaded', function () {
	// Manually load plugin
	require dirname( dirname( __DIR__ ) ) . '/' . PLUGIN_FILE;
} );

// Removes all sql tables on shutdown
// Do this action last
tests_add_filter( 'shutdown', 'drop_tables', 999999 );

require $_tests_dir . '/includes/bootstrap.php';
require __DIR__ . '/base-test.php';

remove_action( 'admin_init', '_maybe_update_themes' );
remove_action( 'admin_init', '_maybe_update_core' );
remove_action( 'admin_init', '_maybe_update_plugins' );

