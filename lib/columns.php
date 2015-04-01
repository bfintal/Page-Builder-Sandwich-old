<?php
/**
 * Sandwich Columns
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * PB Sandwich Column Class
 */
class GambitPBSandwichColumns {
	

	/**
	 * Hook onto WordPress
	 *
	 * @return	void
	 */
	function __construct() {
		add_action( 'the_content', array( $this, 'cleanColumnOutput' ) );
		add_action( 'admin_head', array( $this, 'addColumnButton' ) );
		add_action( 'save_post', array( $this, 'rememberColumnStyles' ), 10, 3 );
		add_action( 'wp_head', array( $this, 'renderColumnStyles' ) );
		add_action( 'admin_footer', array( $this, 'addColumnTemplates' ) );
	}
	
	
	/**
	 * Since we are essentially creating tables in the visual composer, we should convert these tables
	 * into divs for the frontend
	 *
	 * @param	$content string The content being outputted in the frontend
	 * @return	string The modified content
	 */
	public function cleanColumnOutput( $content ) {
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
	 * Adds our column button in the TinyMCE visual editor
	 *
	 * @return	void
	 */
	public function addColumnButton() {
	
	    // check user permissions
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		    return;
	    }
	
	    // check if WYSIWYG is enabled
        add_filter( 'mce_buttons', array( $this, 'registerTinyMCEButton' ) );
		
		$columnVars = array(
			'dummy_content' => __( 'Column text', 'pbsandwich' ),
			'modal_title' => __( 'Columns', 'pbsandwich' ),
        	'modal_description' => __( 'Enter a composition here of column ratios separated by spaces.<br>Make sure the ratios sum up to 1.<br>For example: ', 'pbsandwich' ),
			'custom_columns' => __( 'Custom Columns', 'pbsandwich' ),
			'column_1' => sprintf( __( '%s Column', 'pbsandwich' ), 1 ),
			'column_2' => sprintf( __( '%s Columns', 'pbsandwich' ), 2 ),
			'column_3' => sprintf( __( '%s Columns', 'pbsandwich' ), 3 ),
			'column_4' => sprintf( __( '%s Columns', 'pbsandwich' ), 4 ),
			'column_1323' => sprintf( __( '%s Columns', 'pbsandwich' ), '1/3 + 2/3' ),
			'column_2313' => sprintf( __( '%s Columns', 'pbsandwich' ), '2/3 + 1/3' ),
			'column_141214' => sprintf( __( '%s Columns', 'pbsandwich' ), '1/4 + 1/2 + 1/4' ),
			'delete' => __( 'Delete', 'pbsandwich' ),
			'edit' => __( 'Edit', 'pbsandwich' ),
			'change_column' => __( 'Change Column', 'pbsandwich' ),
			'clone' => __( 'Clone', 'pbsandwich' ),
			'change_columns' => __( 'Change Columns', 'pbsandwich' ),
			'cancel' => __( 'Cancel', 'pbsandwich' ),
			'preset' => __( 'Preset', 'pbsandwich' ),
			'preset_desc' => __( 'You can change the number of columns below:', 'pbsandwich' ),
			'use_custom' => __( 'Use custom', 'pbsandwich' ),
			'custom' => __( 'Custom', 'pbsandwich' ),
			'non_sortable_elements' => $this->formNonSortableElements(),
			'clone_row' => __( 'Clone Row', 'pbsandwich' ),
			'delete_row' => __( 'Delete Row', 'pbsandwich' ),
			'edit_row' => __( 'Edit Row', 'pbsandwich' ),
			'edit_area' => __( 'Edit Area', 'pbsandwich' ),
			'clone_area' => __( 'Clone Area', 'pbsandwich' ),
			'delete_area' => __( 'Delete Area', 'pbsandwich' ),
			'column' => __( 'Column', 'pbsandwich' ),
			'row' => __( 'Row', 'pbsandwich' ),	
			
			// Column edit modal
			'column_settings' => __( 'Column Settings', 'pbsandwich' ),
			'styles' => __( 'Styles', 'pbsandwich' ),
			'style' => __( 'Style', 'pbsandwich' ),
			'border' => __( 'Border', 'pbsandwich' ),
			'padding' => __( 'Padding', 'pbsandwich' ),
			'none' => __( 'None', 'pbsandwich' ),
			'dotted' => __( 'Dotted', 'pbsandwich' ),
			'dashed' => __( 'Dashed', 'pbsandwich' ),
			'solid' => __( 'Solid', 'pbsandwich' ),
			'double' => __( 'Double', 'pbsandwich' ),
			'groove' => __( 'Groove', 'pbsandwich' ),
			'ridge' => __( 'Ridge', 'pbsandwich' ),
			'inset' => __( 'Inset', 'pbsandwich' ),
			'outset' => __( 'Outset', 'pbsandwich' ),
			'color' => __( 'Color', 'pbsandwich' ),
			'radius' => __( 'Radius', 'pbsandwich' ),
			'background' => __( 'Background', 'pbsandwich' ),
			'image' => __( 'Image', 'pbsandwich' ),
			'size' => __( 'Size', 'pbsandwich' ),
			'inherit' => __( 'Inherit', 'pbsandwich' ),
			'cover' => __( 'Cover', 'pbsandwich' ),
			'contain' => __( 'Contain', 'pbsandwich' ),
			'repeat' => __( 'Repeat', 'pbsandwich' ),
			'repeatx' => __( 'Repeat-x', 'pbsandwich' ),
			'repeaty' => __( 'Repeat-y', 'pbsandwich' ),
			'norepeat' => __( 'No-repeat', 'pbsandwich' ),
			'round' => __( 'Round', 'pbsandwich' ),
			'space' => __( 'Space', 'pbsandwich' ),
			'position' => __( 'Position', 'pbsandwich' ),
			'margin' => __( 'Margin', 'pbsandwich' ),
			'row_settings' => __( 'Row Settings', 'pbsandwich' ),
			
		);
		$columnVars = apply_filters( 'pbs_column_vars', $columnVars );
		$columnVars = apply_filters( 'pbs_js_vars', $columnVars );
		
		// Print out our variables
		?>
		<script type="text/javascript">
		var pbsandwich_column = <?php echo json_encode( $columnVars ) ?>;
        </script>
		<?php
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
	
	
	/**
	 * Adds the column template files
	 *
	 * @return	void
	 * @since	0.11
	 */
	public function addColumnTemplates() {
	    // check user permissions
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		    return;
	    }
	
		include_once PBS_PATH . "/lib/templates/column-toolbar.php";
		include_once PBS_PATH . "/lib/templates/column-change-modal.php";
		include_once PBS_PATH . "/lib/templates/column-custom-modal-description.php";
		include_once PBS_PATH . "/lib/templates/column-area-edit-modal.php";
		include_once PBS_PATH . "/lib/templates/column-row-edit-modal.php";
		
	}
	
	
	/**
	 * Forms a list of elements that when clicked won't initiate a drag
	 *
	 * @return	void
	 */
	protected function formNonSortableElements() {
		// When these elements are clicked, don't drag the element
		$nonSortableElements = 'p,code,blockquote,span,pre,td:not(.pbsandwich_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img';
		$nonSortableElements = apply_filters( 'sc_non_sortable_elements', $nonSortableElements );
		
		// Allow all contents of views to be draggable
		$nonSortableElementsArray = explode( ',', $nonSortableElements );
		$nonSortableElements = '';
		foreach ( $nonSortableElementsArray as $key => $element ) {
			if ( $key > 0 ) {
				$nonSortableElements .= ',';
			}
			$nonSortableElements .= $element . ':not(.wpview-wrap ' . $element . ')';
		}
		
		// Add the toolbar elements
		$nonSortableElements .= empty( $nonSortableElements ) ? '' : ',';
		$nonSortableElements .= '#wp-column-toolbar,.toolbar,.toolbar .dashicons';
		
		return $nonSortableElements;
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
			require_once( PBS_PATH . 'inc/simple_html_dom.php' );
		}
		wp_enqueue_style( 'pbsandwich-frontend', PBS_URL . 'css/frontend.css' );
		
		$columnStyles = '';
	
		
		// Remove stray jQuery sortable classes
		$html = preg_replace( '/(ui-sortable-handle|ui-sortable)/', '', $content );
		$html = str_get_html( $html );

		$tables = $html->find( 'table.pbsandwich_column' );
		$hashes = array();
		while ( count( $tables ) > 0 ) {
			$tr = $html->find( 'table.pbsandwich_column', 0 )->find( 'tr', 0 );
			
			$newDivs = '';
			$styleDump = '';

			// Gather table styles			
			$tableStyles = $html->find( 'table.pbsandwich_column', 0 )->style;
			
			// Remove the default editor styles that do not have any effects:
			
			// width: 100%; height: auto; border: none;			
			$tableStyles = trim( preg_replace( '/(^|\s)width:[^;]+;?\s?/', '', $tableStyles ) );
			$tableStyles = trim( preg_replace( '/(^|\s)height:[^;]+;?\s?/', '', $tableStyles ) );
			$tableStyles = trim( preg_replace( '/(^|\s)border:\s?none;?\s?/', '', $tableStyles ) );
			
			// Gather the column styles, use placeholders for the ID since we have yet to generate the unique ID
			if ( ! empty( $tableStyles ) ) {
				$columnStyles .= '.sandwich.pbsandwich_column_%' . ( count( $hashes ) + 1 ) . '$s { ' . wp_kses( $tableStyles, array(), array() ). ' }';
			}
			$styleDump .= esc_attr( $tableStyles );
			
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
				
				// Remove the widths since we are using classes for those:
				$columnStyle = trim( preg_replace( '/(^|\s)width:[^;]+;\s?/', '', $td->style ) );
				
				// Gather the column styles, use placeholders for the ID since we have yet to generate the unique ID
				if ( ! empty( $columnStyle ) ) {
					$columnStyles .= '.sandwich.pbsandwich_column_%' . ( count( $hashes ) + 1 ) . '$s > div > div:nth-of-type(' . ( $key + 1 ) . ') { ' . wp_kses( $columnStyle, array(), array() ). ' }';
				}
				$styleDump .= esc_attr( $td->style );
			
				$newDivs .= '<div class="' . esc_attr( $td->class ) . '">' . $innerHTML . '</div>';
			}
			
			// Generate the unique ID of this column based on the margin rules it has. (crc32 is fast)
			$hash = crc32( $styleDump );
			$hashes[] = $hash;
			
			
			/**
			 * Build our converted <table>
			 */
			// Our main class
			$tableClasses = array( 'sandwich' );
			// Carry over custom classes
			$tableClasses[] = $html->find( 'table.pbsandwich_column', 0 )->class;
			// Custom styles class
			if ( ! empty( $columnStyles ) ) {
				$tableClasses[] = 'pbsandwich_column_' . $hash;
			}
			$newDivs = '<div class="' . esc_attr( join( ' ', $tableClasses ) ) . '"><div class="row">' . $newDivs . '</div></div>';
						
			$html->find( 'table.pbsandwich_column', 0 )->outertext = $newDivs;
			
			$html = $html->save();
			$html = str_get_html( $html );
		
			$tables = $html->find( 'table.pbsandwich_column' );
		}
		
		// Sanitize the output for security
		$columnStyles = wp_kses( $columnStyles, array(), array() );
		// Make sure our html entities are correct to make our rules work properly
		$columnStyles = html_entity_decode( $columnStyles );
		
		// Insert the hashes
		foreach ( $hashes as $key => $hash ) {
			$columnStyles = str_replace( '%' . ( $key + 1 ) . '$s', $hash, $columnStyles );
		}
		
		return array(
			'content' => (string) $html,
			'styles' => $columnStyles,
		);
	}
	
}

new GambitPBSandwichColumns();