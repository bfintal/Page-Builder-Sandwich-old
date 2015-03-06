<?php
/**
 * Uninstall file
 */

// Bail if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'sandwich_version_upgrade_from' );
