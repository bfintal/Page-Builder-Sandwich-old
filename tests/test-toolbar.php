<?php

class PBSTestToolbar extends WP_UnitTestCase {

	function testClearToolbarButtonArgs() {
		$o = new GambitPBSandwichToolbar();
		$arr = $o->clearToolbarButtonArgs( array() );
		
		$this->assertInternalType( 'array', $arr );
		$this->assertArrayHasKey( 'priority', $arr );
		$this->assertArrayHasKey( 'hash', $arr );
	}
	
	function testAddToolbars() {
		$o = new GambitPBSandwichToolbar();
		$this->assertInternalType( 'array', $o->addToolbars( array() ) );
		$this->assertArrayHasKey( 'toolbar_buttons', $o->addToolbars( array() ) );
		
		add_filter( 'pbs_toolbar_buttons', function( $toolbarButtons ) {
			$toolbarButtons[] = array(
				'label' => 'My Button',
			);
			return $toolbarButtons;
		});
		$toolbars = $o->addToolbars( array() );
		
		$found = false;
		$hashes = array();
		foreach ( $toolbars['toolbar_buttons'] as $toolbar ) {
			if ( $toolbar['label'] == 'My Button' ) {
				$found = true;
			}
			
			$this->assertNotEmpty( $toolbar['hash'], 'Hashes should always be generated' );
			$this->assertNotContains( $toolbar['hash'], $hashes, 'Hashes should be unique' );
			$hashes[] = $toolbar['hash'];
		}
		$this->assertTrue( $found, 'Added toolbar button was not added with pbs_toolbar_buttons' );
	}
	
	function testAddCoreToolbarButtons() {
		$o = new GambitPBSandwichToolbar();
		$this->assertInternalType( 'array', $o->addCoreToolbarButtons( array() ) );
	}
}

