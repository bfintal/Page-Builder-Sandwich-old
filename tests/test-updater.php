<?php

class PBSTestUpdater extends WP_UnitTestCase {


	/**
	 * Test for class conflicts with EDD's updater
	 */
	function testEDDNamespacing() {
		$this->assertFalse( class_exists( 'EDD_SL_Plugin_Updater' ), 'EDD_SL_Plugin_Updater class should be namespaced to PBS_EDD_SL_Plugin_Updater prevent class conflicts' );
		$this->assertTrue( class_exists( 'PBS_EDD_SL_Plugin_Updater' ), 'PBS_EDD_SL_Plugin_Updater class (namespaced from EDD_SL_Plugin_Updater) should be exist' );
	}
	
	
	/**
	 * Test pbs_extension_updater hook
	 */
	function testGatheringEmptyExtensions() {
		// By default there should be no extensions
		$o = new GambitPBSandwichExtUpdater();
		$o->gatherExtensions();
		$this->assertEquals( $o->extensions, array() );

		// Adding these extensions with missing parameters should not add them to the list of extensions
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array();
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'name' => 'extension'
			);
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'file' => 'extension'
			);
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'store_url' => 'extension'
			);
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'name' => 'extension',
				'file' => 'extension'
			);
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'name' => 'extension',
				'store_url' => 'extension'
			);
			return $extensions;
		} );
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'store_url' => 'extension',
				'file' => 'extension'
			);
			return $extensions;
		} );
		$o = new GambitPBSandwichExtUpdater();
		$o->gatherExtensions();
		$this->assertEquals( $o->extensions, array(), 'Not supplying required parameters should not include the extension' );
		

		// Minimum required parameters
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'name' => 'my cool extension',
				'store_url' => 'extension',
				'file' => 'extension',
			);
			return $extensions;
		} );
		$o = new GambitPBSandwichExtUpdater();
		$o->gatherExtensions();
		$this->assertEquals( count( $o->extensions ), 1, 'Minimum required parameters should include the extension' );
		
		// Check for generated slug
		$this->assertTrue( array_key_exists( 'my_cool_extension', $o->extensions ), 'Extension slug is not generated correctly' );
		$this->assertEquals( $o->extensions['my_cool_extension']['slug'], 'my_cool_extension', 'Extension slug is not generated correctly' );
		$this->assertEquals( $o->extensions['my_cool_extension']['ssl'], false, 'SSL is false by default' );
	}
	
	
	/**
	 * Test whether extensions appear in the admin licensing page
	 */
	function testLincenseAdminPage() {
		add_filter( 'pbs_extension_updater', function( $extensions ) {
			$extensions[] = array(
				'name' => 'my cool extension',
				'store_url' => 'extension',
				'file' => 'extension',
			);
			return $extensions;
		} );
		$o = new GambitPBSandwichExtUpdater();
		$o->gatherExtensions();
		
		ob_start();
		$o->renderLicensesPage();
		$result = ob_get_clean();
		
		
		$this->assertEquals( preg_match( '/my cool extension/', $result ), 1, 'Extension is not appearing in the license admin page' );
		$this->assertEquals( preg_match( '/<input\s/', $result ), 1, 'Extension is not appearing in the license admin page' );
		
	}
}

