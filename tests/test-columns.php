<?php

class PBSTestColumns extends WP_UnitTestCase {
	
	
	/**
	 * Test pbs_extension_updater hook
	 */
	function testRenderColumnStyles() {
		global $post;
		$post = $this->factory->post->create_and_get();
			
		// Empty styles should output no style tag
		$o = new GambitPBSandwichColumns();
		ob_start();
		$o->renderColumnStyles();
		$styles = ob_get_clean();
		$this->assertEmpty( $styles, 'Posts without any post meta should not generate column style output' );

		// Create styles for the post
		$dummyStyles = 'div { color: red; }';
		update_post_meta( $post->ID, 'pbsandwich_styles', $dummyStyles );
			
		// The styles should match the outputted styles
		$o = new GambitPBSandwichColumns();
		ob_start();
		$o->renderColumnStyles();
		$styles = ob_get_clean();
		
		$this->assertEquals( preg_match( '/^<style[^>]+>[^<]+<\/style>$/', $styles ), 1, 'Column style tags are not generated' );
		$this->assertTrue( strpos( $styles, $dummyStyles ) !== false, 'Inputted columns styles are not being included in the generated styles' );


		// Create styles for the post
		update_post_meta( $post->ID, 'pbsandwich_styles', ' ' );
		ob_start();
		$o->renderColumnStyles();
		$styles = ob_get_clean();
		
		$this->assertEmpty( $styles, 'Empty spaces should not generate column style output' );
	}
	
	function testRegisterTinyMCEButton() {
		// Check if pbsandwich_column button was added
		$o = new GambitPBSandwichColumns();
		$buttons = $o->registerTinyMCEButton( array() );
		$this->assertTrue( in_array( 'pbsandwich_column', $buttons ), 'pbsandwich_column was not added to the existing TinyMCE buttons' );
	}
	
	function testRememberColumnStyles() {
		// Test styles should be saved in post meta 'pbsandwich_styles'
		$o = new GambitPBSandwichColumns();

		$_POST = array();
		
		$_POST['content'] = '';
		$post = $this->factory->post->create_and_get();
		$o->rememberColumnStyles( $post->ID, $post, null );
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles', true );
		$this->assertEmpty( $styles, 'Blank posts should render no styles' );
		
		$_POST['content'] = 'Hello world';
		$post = $this->factory->post->create_and_get();
		$o->rememberColumnStyles( $post->ID, $post, null );
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles', true );
		$this->assertEmpty( $styles, 'Normal text posts should render no styles' );
		
		$_POST['content'] = '<table class="pbsandwich_column ui-sortable-handle" style="width: 100%; height: auto; border: none;" border="0">
<tbody class="row">
<tr>
<td class="col-sm-6 ui-sortable" style="width: 50%;" data-wp-columnselect="1">
<p class="ui-sortable-handle">Column text</p>
</td>
<td class="col-sm-6 ui-sortable" style="width: 50%;">
<p class="ui-sortable-handle">Column text</p>
</td>
</tr>
</tbody>
</table>';

		$post = $this->factory->post->create_and_get( array() );
		$o->rememberColumnStyles( $post->ID, $post, null );
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles', true );
		$this->assertEmpty( $styles, 'Rows with no extra styles should render no styles' );
		
		$_POST['content'] = '<table class="pbsandwich_column ui-sortable-handle" style="width: calc(100% - 0px); height: auto; border: 0px none rgb(51, 51, 51); padding: 0px; border-radius: 0px; margin: 10px 0px 20px; background-color: rgb(122, 122, 122); background-size: inherit; background-position: 0% 0%; background-repeat: repeat;" border="0" data-background-image="" data-break-out="">
<tbody class="row">
<tr>
<td class="col-sm-6 ui-sortable" style="width: 50%;">
<p class="ui-sortable-handle">Column text</p>
</td>
<td class="col-sm-6 ui-sortable" style="width: 50%;" data-wp-columnselect="1">
<p class="ui-sortable-handle">Column text</p>
</td>
</tr>
</tbody>
</table>';
		$post = $this->factory->post->create_and_get( array() );
		$o->rememberColumnStyles( $post->ID, $post, null );
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles', true );
		$this->assertNotEmpty( $styles, 'Rows with styles should render styles' );
		
		
		// If $_POST['wp-preview'] = 'dopreview', styles should be saved in post meta 'pbsandwich_styles_preview'
		$_POST['wp-preview'] = 'dopreview';
		$_POST['content'] = '<table class="pbsandwich_column ui-sortable-handle" style="width: calc(100% - 0px); height: auto; border: 0px none rgb(51, 51, 51); padding: 0px; border-radius: 0px; margin: 10px 0px 20px; background-color: rgb(122, 122, 122); background-size: inherit; background-position: 0% 0%; background-repeat: repeat;" border="0" data-background-image="" data-break-out="">
<tbody class="row">
<tr>
<td class="col-sm-6 ui-sortable" style="width: 50%;">
<p class="ui-sortable-handle">Column text</p>
</td>
<td class="col-sm-6 ui-sortable" style="width: 50%;" data-wp-columnselect="1">
<p class="ui-sortable-handle">Column text</p>
</td>
</tr>
</tbody>
</table>';
		$post = $this->factory->post->create_and_get( array() );
		$o->rememberColumnStyles( $post->ID, $post, null );
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles_preview', true );
		$this->assertNotEmpty( $styles, 'When previewed, rows with styles should render styles' );
	}
	
	function testParseColumnContent() {
		// Test different contents against the correct content output
		$o = new GambitPBSandwichColumns();
		
		$content = '';
		$result = $o->parseColumnContent( $content );
		$this->assertEquals( $result, array( 'content' => '', 'styles' => '', ), 'Parsing empty content should return empty content' );
		
		$content = '<table class="pbsandwich_column ui-sortable-handle" style="width: calc(100% - 0px); height: auto; border: 0px none rgb(51, 51, 51); padding: 0px; border-radius: 0px; margin: 10px 0px 20px; background-color: rgb(122, 122, 122); background-size: inherit; background-position: 0% 0%; background-repeat: repeat;" border="0" data-background-image="" data-break-out="" data-row-foo="bar">
<tbody class="row">
<tr>
<td class="col-sm-6 ui-sortable" style="width: 50%; border-radius: 10px;" data-column-foo="bar">
should_be_wrapped
</td>
<td class="col-sm-6 ui-sortable" style="width: 50%;" data-wp-columnselect="1">
<p class="ui-sortable-handle">should_not_be_wrapped</p>
</td>
</tr>
</tbody>
</table><table class="pbsandwich_column ui-sortable-handle" style="width: calc(100% - 0px); height: auto; border: 0px none rgb(51, 51, 51); padding: 0px; border-radius: 0px; margin: 10px 0px 20px; background-color: rgb(122, 122, 122); background-size: inherit; background-position: 0% 0%; background-repeat: repeat;" border="0" data-background-image="" data-break-out="" data-row-foo="bar">
<tbody class="row">
<tr>
<td class="col-sm-6 ui-sortable" style="width: 50%; border-radius: 10px;" data-column-foo="bar">
<div>divs_not_wrapped</div>
</td>
<td class="col-sm-6 ui-sortable" style="width: 50%;" data-wp-columnselect="1">
<small>small_should_be_wrapped</small>
</td>
</tr>
</tbody>
</table>';
		$result = $o->parseColumnContent( $content );
		
		// Check for .sandwich_column class
		$this->assertEquals( preg_match( '/pbsandwich_column/', $result['content'] ), 1, 'pbsandwich_column does not exist' );
		
		// Test data- attributes in rows
		$this->assertEquals( preg_match( '/data-row-foo="bar"/', $result['content'] ), 1, 'Row data-* attributes should be carried over' );
		
		// Test generated styles for rows
		$this->assertEquals( preg_match( '/border-radius: 0px/', $result['styles'] ), 1, 'Styles were not compiled for rows' );
		
		// Test data- attributes in columns
		$this->assertEquals( preg_match( '/data-column-foo="bar"/', $result['content'] ), 1, 'Column data-* attributes should be carried over' );
		
		// Test styles in columns
		$this->assertEquals( preg_match( '/border-radius: 10px/', $result['styles'] ), 1, 'Styles were not compiled for columns' );
		
		// Test column contents should always be wrapped in paragraph tags unless it's invalid html
		$this->assertEquals( preg_match( '/<p[^>]*>should_be_wrapped<\/p>/', $result['content'] ), 1, 'Unwrapped column text should be wrapped in paragraph tags' );
		$this->assertEquals( preg_match( '/<p[^>]*>should_not_be_wrapped<\/p>/', $result['content'] ), 1, 'Paragraph tags in column text should remain wrapped in paragraph tags' );
		$this->assertEquals( preg_match( '/<p[^>]*>[^<]*<p[^>]+/', $result['content'] ), 0, 'Cascading paragraph tags should not be present' );
		$this->assertEquals( preg_match( '/<p[^>]*><div>divs_not_wrapped/', $result['content'] ), 0, 'Column contents wrapped in block elements should not be wrapped in paragraph tags' );
		$this->assertEquals( preg_match( '/<p[^>]*><small>small_should_be_wrapped/', $result['content'] ), 0, 'Inline tagged column contents should be wrapped in paragraph tags' );
		
		// Test bootstrap grid
		$this->assertEquals( preg_match( '/col-\w+-\d+/', $result['content'] ), 1, 'Bootstrap grids should be implemented in columns & rows' );
		
		// Test output should never contain tables,tbody,thead,tfooter,tr,td,th
		$this->assertEquals( preg_match( '/<(table|tbody|thead|tfooter|tr|td|th)>/', $result['content'] ), 0, 'Table tags should be converted into divs' );
	}
	
	function testAddColumnToolbarButtons() {
		// Check if all toolbar buttons exist
		$o = new GambitPBSandwichColumns();
		$buttons = $o->addColumnToolbarButtons( array() );
		
		$this->assertNotEquals( $this->searchForAction( 'column-edit-area', $buttons ), null, 'Column edit button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-clone-area', $buttons ), null, 'Column clone button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-remove-area', $buttons ), null, 'Column remove button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'row-align-left', $buttons ), null, 'Row align left button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'row-align-center', $buttons ), null, 'Row align center button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'row-align-right', $buttons ), null, 'Row align right button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'row-align-none', $buttons ), null, 'Row align none button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-edit-row', $buttons ), null, 'Row edit button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-columns', $buttons ), null, 'Row column layout button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-clone-row', $buttons ), null, 'Row clone button does not exist' );
		$this->assertNotEquals( $this->searchForAction( 'column-remove-row', $buttons ), null, 'Row remove button does not exist' );
	}
	
	function searchForAction( $id, $array ) {
		foreach ( $array as $key => $val ) {
			if ( ! empty( $val['action'] ) && $val['action'] === $id ) {
				return $key;
			}
		}
		return null;
	}
}

