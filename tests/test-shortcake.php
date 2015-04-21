<?php

class PBSTestShortcake extends WP_UnitTestCase {

	function testShortcakeHeaders() {
		$myfile = fopen( PBS_PATH . 'inc/shortcake/shortcode-ui.php', "r") or die( "Unable to open file!" );
		$fileContents = fread( $myfile, filesize( PBS_PATH . 'inc/shortcake/shortcode-ui.php' ) );
		fclose( $myfile );
		
		$this->assertEquals( preg_match( '/\* Plugin Name:/i', $fileContents ), 0, 'shortcake.php should not have plugin headers or else plugin activation will error out.' );
		$this->assertEquals( preg_match( '/\* Plugin URI:/i', $fileContents ), 0, 'shortcake.php should not have plugin headers or else plugin activation will error out.' );
		$this->assertEquals( preg_match( '/\* Description:/i', $fileContents ), 0, 'shortcake.php should not have plugin headers or else plugin activation will error out.' );
		$this->assertEquals( preg_match( '/\* Version:/i', $fileContents ), 0, 'shortcake.php should not have plugin headers or else plugin activation will error out.' );
		
		$this->assertEquals( preg_match( '/exit;/', $fileContents ), 1, 'shortcake.php should have an exit if called directly for CWE-200 compliance.' );
	}

	function testShortcakeUI() {
		$myfile = fopen( PBS_PATH . 'inc/shortcake/inc/class-shortcode-ui.php', "r") or die( "Unable to open file!" );
		$fileContents = fread( $myfile, filesize( PBS_PATH . 'inc/shortcake/inc/class-shortcode-ui.php' ) );
		fclose( $myfile );
		
		$this->assertEquals( preg_match( '/private \$shortcodes/', $fileContents ), 0, 'class-shortcode-ui.php $shortcodes property should be public to allow unit testing.' );
		$this->assertEquals( preg_match( '/public \$shortcodes/', $fileContents ), 1, 'class-shortcode-ui.php $shortcodes property should be public to allow unit testing.' );
	}
	
	function testLogoutUserForShortcodeRendering() {
		// Log in the user
		wp_set_current_user(1);
		$this->assertTrue( is_user_logged_in(), 'User can log in' );
		
		$o = new GambitPBSandwichShortcake();
		GambitPBSandwichShortcake::$shortcodes = array();
		
		$o->logoutUserForShortcodeRendering( '[dummy_shortcode]' );
		$this->assertTrue( is_user_logged_in(), 'Shortcodes not specified should not logout the user' );
		
		sandwich_add_logged_out_shortcode( 'dummy_shortcode' );
		$o->logoutUserForShortcodeRendering( '[dummy_shortcode]' );
		$this->assertFalse( is_user_logged_in(), 'Shortcodes specified should logout the user' );
	}

}