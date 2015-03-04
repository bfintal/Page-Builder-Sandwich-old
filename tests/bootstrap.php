<?php

$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'Page-Builder-Sandwich/pb-sandwich.php' ),
);

$_tests_dir = getenv('WP_TESTS_DIR');
if ( !$_tests_dir ) $_tests_dir = '/tmp/wordpress-tests-lib';

require $_tests_dir . '/includes/bootstrap.php';