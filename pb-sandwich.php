<?php
/**
* Plugin Name: Page Builder Sandwich
* Plugin URI: https://github.com/gambitph/Page-Builder-Sandwich
* Description: The native visual editor page builder. Empower your visual editor with drag and drop & column capabilities.
* Version: 0.6-dev
* Author: Benjamin Intal - Gambit Technologies Inc
* Author URI: http://gambit.ph
* License: GPL2
* Text Domain: pbsandwich
* Domain Path: /languages
*/

// Used for tracking the version used
defined( 'PBS_VERSION' ) or define( 'PBS_VERSION', '0.6-dev' );

// Used for file includes
defined( 'PBS_PATH' ) or define( 'PBS_PATH', trailingslashit( dirname( __FILE__ ) ) );

require_once( PBS_PATH . 'lib/shortcode/hello-dolly.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-contact-form.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-googlemaps.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-portfolio.php' );
require_once( PBS_PATH . 'lib/shortcode/toggle.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-archives.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-calendar.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-categories.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-custom-menu.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-meta.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-pages.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-recent-comments.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-recent-posts.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-rss.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-search.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-tag-cloud.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-jetpack-display-wordpress-posts.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-jetpack-facebook-like-box.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-jetpack-gravatar-profile.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-jetpack-rss-links.php' );
require_once( PBS_PATH . 'lib/shortcode/widget-jetpack-subscriptions.php' );


/**
 * PB Sandwich Class
 */
class GambitPBSandwich {
	

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
		add_filter( 'tiny_mce_before_init', array( $this, 'addSandwichBootstrap' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadFrontendScripts' ) );
		add_action( 'init', array( $this, 'loadShortcake' ), 1 );
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
	
	public function loadFrontendScripts() {
		wp_enqueue_style( 'dashicons' );
	    wp_enqueue_style( 'pbsandwich', plugins_url( 'css/frontend.css', __FILE__ ), array(), PBS_VERSION );
	    wp_enqueue_script( 'pbsandwich', plugins_url( 'js/min/frontend-min.js', __FILE__ ), array( 'jquery' ), PBS_VERSION );
	}

	
	/**
	 * Add the styles for our column button
	 *
	 * @return	void
	 */
	public function columnButtonIcon() {
	    wp_enqueue_style( 'pbsandwich-admin', plugins_url( 'css/admin.css', __FILE__ ) );
	    wp_enqueue_script( 'pbsandwich-admin', plugins_url( 'js/min/admin-min.js', __FILE__ ), array( 'jquery' ), PBS_VERSION );
	}
	
	
	/**
	 * Adds our column plugin in TinyMCE
	 *
	 * @param	$pluginArray An array of TinyMCE plugins
	 * @return	An array of TinyMCE plugins
	 */
	public function addTinyMCEPlugin( $pluginArray ) {
	    $pluginArray['pbsandwich'] = plugins_url( 'js/min/editor-min.js', __FILE__ );
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
	 * Loads Shortcake UI if not available
	 *
	 * @return	void
	 */
	public function loadShortcake() {
		// Don't do anything when we're activating a plugin to prevent errors
		// on redeclaring Shortcake classes
		if ( is_admin() ) {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['plugin'] ) ) {
			    if ( $_GET['action'] == 'activate' ) {
			        return;
			    }
			}
		}
		
		// Include Shortcake if we don't have it yet
		if ( ! class_exists( 'Shortcode_UI' ) ) {
			require_once( PBS_PATH . 'inc/shortcake/shortcode-ui.php' );
		}
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
	
	    // check if WYSIWYG is enabled
	    if ( get_user_option( 'rich_editing' ) == 'true' ) {
	        add_filter( 'mce_external_plugins', array( $this, 'addTinyMCEPlugin' ) );
	        add_filter( 'mce_buttons', array( $this, 'registerTinyMCEButton' ) );
			
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
				non_sortable_elements: '<?php echo addslashes( $this->formNonSortableElements() ) ?>'
	        };
	        </script>
			<?php
	    }
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
			require_once( 'inc/simple_html_dom.php' );
		}
		wp_enqueue_style( 'pbsandwich-frontend', plugins_url( 'css/frontend.css', __FILE__ ) );
		
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
				if ( empty( $td->class ) ) {
					$columnStyles .= '.pbsandwich_column_%' . ( count( $hashes ) + 1 ) . '$s > div > div:nth-of-type(' . ( $key + 1 ) . ') { ' . esc_attr( $td->style ) . ' }';
				}
				$styleDump .= esc_attr( $td->style );
			
				$newDivs .= '<div class="' . esc_attr( $td->class ) . '">' . $innerHTML . '</div>';
			}
			
			// Generate the unique ID of this column based on the margin rules it has. (crc32 is fast)
			$hash = crc32( $styleDump );
			$hashes[] = $hash;
			
			// This is our converted <table>
			$customClass = empty( $columnStyles ) ? 'sandwich' : 'pbsandwich_column_' . $hash; // Backward compatibility
			$newDivs = '<div class="pbsandwich_column ' . $customClass . '"><div class="row">' . $newDivs . '</div></div>';
						
			$html->find( 'table.pbsandwich_column', 0 )->outertext = $newDivs;
			
			$html = $html->save();
			$html = str_get_html( $html );
		
			$tables = $html->find( 'table.pbsandwich_column' );
		}
		
		// Insert the hashes
		foreach ( $hashes as $key => $hash ) {
			$columnStyles = str_replace( '%' . ( $key + 1 ) . '$s', $hash, $columnStyles );
		}
		
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
	
	public function addSandwichBootstrap( $init ) {
		$init['body_class'] = 'sandwich';
		return $init;
	}
	
	public static function printDisabledShortcakeStlyes( $shortcode, $label ) {
		?>
		<style>
		.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"]:after {
			content: "<?php echo addslashes( $label ) ?>";
			top: 50%;
			position: absolute;
			text-align: center;
			transform: translateY(-50%);
			background: rgba(255,255,255,.8);
			padding: .3em .5em;
			font-style: italic;
			left: 0;
		}
		.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"] {
			box-shadow: none;
			pointer-events: none;
		}
		.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"] > * {
			opacity: .3;
		}
		</style>
		<?php
	}
	
}
new GambitPBSandwich();