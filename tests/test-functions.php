<?php

class PBSTestFunctions extends WP_UnitTestCase {

	function testReturns() {
		// replace this with some actual testing code
		$this->assertInternalType( 'array', sandwich_functions_display_order() );
		$this->assertInternalType( 'array', sandwich_functions_display_dir() );
		
		$this->assertInternalType( 'array', sandwich_functions_posttype_list('post') );
		$this->assertCount( 1, sandwich_functions_posttype_list('post'), 'Should have the first element as the "select one" element' );
		$this->factory->post->create();
		$this->assertCount( 2, sandwich_functions_posttype_list('post') );
		$this->factory->post->create_many( 8 );
		$this->assertCount( 10, sandwich_functions_posttype_list('post') );
		
		$this->assertInternalType( 'array', sandwich_functions_taxonomy_list() );

		$this->assertInternalType( 'array', sandwich_functions_term_list( 'dummy' ) );
		$this->assertCount( 1, sandwich_functions_term_list( 'dummy' ) );
		$this->assertCount( 2, sandwich_functions_term_list( 'category' ) );
	}
}

