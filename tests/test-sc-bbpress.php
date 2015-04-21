<?php

class PBSTestShortcodeBBPress extends WP_UnitTestCase {

	protected $shortcodes = array( 
		'bbp-forum-index',
		'bbp-forum-form',
		'bbp-single-forum',
		'bbp-topic-index',
		'bbp-topic-form',
		'bbp-single-topic',
		'bbp-reply-form',
		'bbp-single-reply',
		'bbp-topic-tags',
		'bbp-single-tag',
		'bbp-single-view',
		'bbp-search',
		'bbp-search-form',
		'bbp-login',
		'bbp-register',
		'bbp-lost-pass',
		'bbp-stats',
	);
	
	function testUI() {
		// When plugin is inactive, do not display the shortcodes
		$shortcake = new Shortcode_UI();
		$shortcake->shortcodes = array();
		
		$o = new GambitPBSandwichShortcodeBBPress();
		$o->sandwich_bbp_shortcodes();
		
		foreach ( $this->shortcodes as $tag ) {
			$this->assertEmpty( $shortcake->get_shortcode( $tag ), $tag . ' should should not be included if parent plugin is deactivated' );
		}

		// When plugin is active, display the shortcodes
		$shortcake = Shortcode_UI::get_instance();
		$shortcake->shortcodes = array();
		
		$result = activate_plugin( 'bbpress/bbpress.php' );
		
		$o = new GambitPBSandwichShortcodeBBPress();
		$o->sandwich_bbp_shortcodes();

		foreach ( $this->shortcodes as $tag ) {
			$this->assertNotEmpty( $shortcake->get_shortcode( $tag ), $tag . ' should should be included if parent plugin is activated' );
		}
	}
	
}