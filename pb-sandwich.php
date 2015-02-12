<?php
/**
* Plugin Name: Page Builder Sandwich
* Plugin URI: https://github.com/gambitph/Page-Builder-Sandwich
* Description: Turn your typical WordPress visual editor into a super charged visual-editor-page-builder hybrid
* Version: 0.1
* Author: Benjamin Intal - Gambit Technologies Inc
* Author URI: http://gambit.ph
* License: GPL2
* Text Domain: pbsandwich
* Domain Path: /languages
*/


/**
 * Column Shortcodeless Columns Class
 */
class GambitShortcodelessColumns {
	

	/**
	 * Hook onto WordPress
	 *
	 * @return	void
	 */
	function __construct() {
		add_action( 'the_content', array( $this, 'cleanOutput' ) );
		add_action( 'admin_init', array( $this, 'addEditorColumnStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'columnButtonIcon' ) );
		add_action( 'admin_head', array( $this, 'addColumnButton' ) );
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadjQuerySortable' ) );
		add_action( 'save_post', array( $this, 'rememberColumnStyles' ), 10, 3 );
		add_action( 'wp_head', array( $this, 'renderColumnStyles' ) );
	}

	
	/**
	 * Load our translations
	 *
	 * @return	void
	 */
	public function loadTextDomain() {
		load_plugin_textdomain( 'pbsandwich', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}

	
	/**
	 * Add the styles for our "table" columns
	 *
	 * @return	void
	 */
	public function addEditorColumnStyles() {
	    add_editor_style( plugins_url( 'css/editor.css', __FILE__ ) );
	}

	
	/**
	 * Add the styles for our column button
	 *
	 * @return	void
	 */
	public function columnButtonIcon() {
	    wp_enqueue_style( 'pbsandwich-admin', plugins_url( 'css/column-admin.css', __FILE__ ) );
	}
	
	
	/**
	 * Adds our column plugin in TinyMCE
	 *
	 * @param	$pluginArray An array of TinyMCE plugins
	 * @return	An array of TinyMCE plugins
	 */
	public function addTinyMCEPlugin( $pluginArray ) {
	    $pluginArray['pbsandwich_column'] = plugins_url( 'js/column-button.js', __FILE__ );
	    return $pluginArray;
	}
	
	
	/**
	 * Registers our column button in TinyMCE
	 *
	 * @param	$buttons array Existing TinyMCE buttons
	 * @return	An array of TinyMCE buttons
	 */
	public function registerTinyMCEButton( $buttons ) {
	   array_push( $buttons, 'pbsandwich_column' );
	   return $buttons;
	}
	
	
	/**
	 * Enqueues jQuery sortable
	 *
	 * @return	void
	 */
	public function loadjQuerySortable() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}
	
	
	/**
	 * Adds our column button in the TinyMCE visual editor
	 *
	 * @return	void
	 */
	public function addColumnButton() {
	    global $typenow;
	
	    // check user permissions
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		    return;
	    }
	
	    // verify the post type
	    if( ! in_array( $typenow, array( 'post', 'page' ) ) ) {
	        return;
		}
	
	    // check if WYSIWYG is enabled
	    if ( get_user_option( 'rich_editing' ) == 'true' ) {
	        add_filter( 'mce_external_plugins', array( $this, 'addTinyMCEPlugin' ) );
	        add_filter( 'mce_buttons', array( $this, 'registerTinyMCEButton' ) );
			
			$nonSortableElements = 'p,code,blockquote,span,pre,td:not(.pbsandwich_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img,#wp-column-toolbar,.toolbar,.toolbar .dashicons';
			$nonSortableElements = apply_filters( 'sc_non_sortable_elements', $nonSortableElements );
			
			?>
			<script type="text/javascript">
	        var pbsandwich_column = {
				dummy_content: '<?php echo addslashes( __( 'Column text', 'default' ) ) ?>',
				modal_title: '<?php echo addslashes( __( 'Columns', 'default' ) ) ?>',
	        	modal_description: '<?php echo addslashes( __( 'Enter a composition here of column ratios separated by spaces.<br>Make sure the ratios sum up to 1.<br>For example: ', 'default' ) ) ?>',
				custom_columns: '<?php echo addslashes( __( 'Custom Columns', 'default' ) ) ?>',
				columns: '<?php echo addslashes( __( '%s Columns', 'default' ) ) ?>',
				delete: '<?php echo addslashes( __( 'Delete', 'default' ) ) ?>',
				edit: '<?php echo addslashes( __( 'Edit', 'default' ) ) ?>',
				change_column: '<?php echo addslashes( __( 'Change Column', 'default' ) ) ?>',
				clone: '<?php echo addslashes( __( 'Clone', 'default' ) ) ?>',
				change_columns: '<?php echo addslashes( __( 'Change Columns', 'default' ) ) ?>',
				cancel: '<?php echo addslashes( __( 'Cancel', 'default' ) ) ?>',
				preset: '<?php echo addslashes( __( 'Preset', 'default' ) ) ?>',
				preset_desc: '<?php echo addslashes( __( 'You can change the number of columns below:', 'default' ) ) ?>',
				use_custom: '<?php echo addslashes( __( 'Use custom', 'default' ) ) ?>',
				custom: '<?php echo addslashes( __( 'Custom', 'default' ) ) ?>',
				non_sortable_elements: '<?php echo addslashes( $nonSortableElements ) ?>'
	        };
	        </script>
			<?php
	    }
	}
	
	
	/**
	 * Parses the html content, and fixes the columns. <table>s are converted into <div>s, and the
	 * styles (margins) are separated.
	 *
	 * @param	$content string The content being outputted in the frontend
	 * @return	string The modified content
	 */
	protected function parseColumnContent( $content ) {
		// simple_html_dom errors out when we don't have any content
		$contentChecker = trim( $content );
		if ( empty( $contentChecker ) ) {
			return array(
				'content' => $content,
				'styles' => '',
			);
		}
		
		if ( ! function_exists( 'file_get_html' ) ) {
			require_once( 'inc/simple_html_dom.php' );
		}
		wp_enqueue_style( 'pbsandwich_columns', plugins_url( 'css/columns.css', __FILE__ ) );
		
		$columnStyles = '';
	
		$html = str_get_html( $content );

		$tables = $html->find( 'table.pbsandwich_column' );
		$hashes = array();
		while ( count( $tables ) > 0 ) {
			$tr = $html->find( 'table.pbsandwich_column', 0 )->find( 'tr', 0 );
			
			$newDivs = '';
			$styleDump = '';
			
			foreach ( $tr->children() as $key => $td ) {
				if ( $td->tag != 'td' ) {
					continue;
				}
				
				// Only add in paragraph tags if there aren't any. 
				// This is to ensure that the spacing remains correct.
				$innerHTML = $td->innertext;
				if ( preg_match( '/<p>/', $innerHTML ) !== false ) {
					$innerHTML = '<p>' . $td->innertext . '</p>';
				}
				
				// Gather the column styles, use placeholders for the ID since we have yet to generate the unique ID
				$columnStyles .= '.pbsandwich_column_%' . ( count( $hashes ) + 1 ) . '$s > div:nth-of-type(' . ( $key + 1 ) . ') { ' . esc_attr( $td->style ) . ' }';
				$styleDump .= esc_attr( $td->style );
			
				$newDivs .= '<div>' . $innerHTML . '</div>';
			}
			
			// Generate the unique ID of this column based on the margin rules it has. (crc32 is fast)
			$hash = crc32( $styleDump );
			$hashes[] = $hash;
			
			// This is our converted <table>
			$newDivs = '<div class="pbsandwich_column pbsandwich_column_' . $hash . '">' . $newDivs . '</div>';
						
			$html->find( 'table.pbsandwich_column', 0 )->outertext = $newDivs;
			
			$html = $html->save();
			$html = str_get_html( $html );
		
			$tables = $html->find( 'table.pbsandwich_column' );
		}
		
		// Insert the hashes
		foreach ( $hashes as $key => $hash ) {
			$columnStyles = str_replace( '%' . ( $key + 1 ) . '$s', $hash, $columnStyles );
		}
		
		// Remove stray jQuery sortable classes
		$html = preg_replace( '/ui-sortable-handle/', '', $html );
		
		return array(
			'content' => (string) $html,
			'styles' => $columnStyles,
		);
	}
	
	
	/**
	 * Since we are essentially creating tables in the visual composer, we should convert these tables
	 * into divs for the frontend
	 *
	 * @param	$content string The content being outputted in the frontend
	 * @return	string The modified content
	 */
	public function cleanOutput( $content ) {
		$parsed = $this->parseColumnContent( $content );
		return $parsed[ 'content' ];
	}
	
	
	/**
	 * Instead of retaining the inline styles of the columns, gather the styles upon saving and
	 * save it as post meta, we will use that in `wp_head` to render the styles
	 *
	 * @param	$content string The content being outputted in the frontend
	 * @return	string The modified content
	 */
	public function rememberColumnStyles( $postID, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( empty( $_POST['content'] ) ) {
			return;
		}
		if ( get_post_status( $postID ) === 'trash' ) {
			return;
		}
		
		// If the post is being previewed, save it differently so as not to overwrite
		// the currently saved styles
		$suffix = '';
		if ( ! empty( $_POST['wp-preview'] ) ) {
			if ( $_POST['wp-preview'] == 'dopreview' ) {
				$suffix = '_preview';
			}
		}
		
		// Generate the styles & save as post meta
		$parsed = $this->parseColumnContent( stripslashes( $_POST[ 'content' ] ) );
		update_post_meta( $postID, 'pbsandwich_styles' . $suffix, $parsed[ 'styles' ] );
	}
	
	
	/**
	 * Gets the column styles saved within the post/page. Saved styles are from the `save_post`
	 * action and are saved as post meta data.
	 *
	 * @return	void
	 */
	public function renderColumnStyles() {
		global $post;
		if ( empty( $post ) ) {
			return;
		}
		
		// 2 sets of styles are saved, preview & published, get what we need
		$suffix = '';
		if ( is_preview() ) {
			$suffix = '_preview';
		}
		
		$styles = get_post_meta( $post->ID, 'pbsandwich_styles' . $suffix, true );
		if ( empty( $styles ) ) {
			return;
		}
		
		echo '<style id="pbsandwich_column">' . $styles . '</style>';
	}
	
}
new GambitShortcodelessColumns();