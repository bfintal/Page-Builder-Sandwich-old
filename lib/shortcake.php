<?php
/**
 * Stuff that modify the behavior of Shortcake
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Triggers the shortcode preview to be rendered in a logged out state
 */
function sandwich_add_logged_out_shortcode( $shortcode ) {
	GambitPBSandwichShortcake::$shortcodes[] = $shortcode;
}


/**
 * PB Sandwich Shortcake Class
 */
class GambitPBSandwichShortcake {
	
	public static $shortcodes = array();

	/**
	 * Hook onto WordPress
	 *
	 * @return	void
	 */
	function __construct() {
		add_action( 'shortcode_ui_before_do_shortcode', array( $this, 'logoutUserForShortcodeRendering' ) );
	}
	
	
	public function logoutUserForShortcodeRendering( $shortcode ) {
		$pattern = '';
		foreach ( self::$shortcodes as $tag ) {
			$pattern .= empty( $pattern ) ? $tag : '|' . $tag;
		}
		
		if ( ! preg_match( '/\[(' . $pattern . ')(]|\s)/', $shortcode ) ) {
			return;
		}
		
		// Logout
		wp_set_current_user( 0 );
	}
	
}
new GambitPBSandwichShortcake();