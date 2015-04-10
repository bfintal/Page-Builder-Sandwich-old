<?php
/**
* Plugin Name: Page Builder Sandwich
* Plugin URI: https://github.com/gambitph/Page-Builder-Sandwich
* Description: The native visual editor page builder. Empower your visual editor with drag and drop & column capabilities.
* Version: 0.12-dev
* Author: Benjamin Intal - Gambit Technologies Inc
* Author URI: http://gambit.ph
* License: GPL2
* Text Domain: pbsandwich
* Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Used for tracking the version used
defined( 'PBS_VERSION' ) or define( 'PBS_VERSION', '0.12-dev' );

// Used for file includes
defined( 'PBS_PATH' ) or define( 'PBS_PATH', trailingslashit( dirname( __FILE__ ) ) );
defined( 'PBS_URL' ) or define( 'PBS_URL', plugin_dir_url( __FILE__ ) );
defined( 'PBS_FILE' ) or define( 'PBS_FILE', __FILE__ );

// General list of essential files for the plugin itself or used by all shortcakes.
require_once( PBS_PATH . 'lib/columns.php' );
require_once( PBS_PATH . 'lib/welcome.php' );
require_once( PBS_PATH . 'lib/shortcake.php' );
require_once( PBS_PATH . 'lib/functions.php' );
require_once( PBS_PATH . 'lib/toolbar.php' );
require_once( PBS_PATH . 'lib/updater.php' );

// General list of shortcakes available to PB Sandwich. They include integrations from 3rd party plugins.
require_once( PBS_PATH . 'lib/shortcode/hello-dolly.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-contact-form.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-googlemaps.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-portfolio.php' );
require_once( PBS_PATH . 'lib/shortcode/jetpack-twitter-timeline.php' );
require_once( PBS_PATH . 'lib/shortcode/toggle.php' );
require_once( PBS_PATH . 'lib/shortcode/progressbar.php' );
require_once( PBS_PATH . 'lib/shortcode/button.php' );
require_once( PBS_PATH . 'lib/shortcode/embed.php' );
require_once( PBS_PATH . 'lib/shortcode/html5video.php');
require_once( PBS_PATH . 'lib/shortcode/html5video-remote.php');
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
require_once( PBS_PATH . 'lib/shortcode/contact-form-7.php' );
require_once( PBS_PATH . 'lib/shortcode/mailchimp.php' );
require_once( PBS_PATH . 'lib/shortcode/ninja-forms.php' );
require_once( PBS_PATH . 'lib/shortcode/bbpress.php' );
require_once( PBS_PATH . 'lib/shortcode/events-manager.php' );

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
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
		add_action( 'admin_init', array( $this, 'addEditorStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadAdminScripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadFrontendScripts' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'addSandwichBootstrap' ) );
		add_action( 'init', array( $this, 'loadShortcake' ), 1 );
		add_action( 'media_buttons', array( $this, 'addShortcodeButton' ), 100 );
		add_action( 'admin_head', array( $this, 'addSandwichPlugin' ) );
		add_filter( 'mce_buttons', array( $this, 'addPageBreakButton' ) );
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
	public function addEditorStyles() {
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
	public function loadAdminScripts() {
	    wp_enqueue_style( 'pbsandwich-admin', plugins_url( 'css/admin.css', __FILE__ ) );
		
		wp_enqueue_script( 'jquery-ui-sortable' );
	    wp_enqueue_script( 'pbsandwich-admin', plugins_url( 'js/min/admin-min.js', __FILE__ ), array( 'jquery' ), PBS_VERSION );
	}
	
	
	/**
	 * Adds our column button in the TinyMCE visual editor
	 *
	 * @return	void
	 */
	public function addSandwichPlugin() {
	
	    // check user permissions
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		    return;
	    }
	
		add_filter( 'mce_external_plugins', array( $this, 'addTinyMCEPlugin' ) );
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
	
	
	public function addShortcodeButton() {
		echo '<a href="#" class="button sandwich-add-shortcode"><span class="wp-media-buttons-icon dashicons dashicons-migrate"></span><span class="wp-media-buttons-icon dashicons dashicons-migrate"></span> ' . __( 'Add Post Element', 'pbsandwich' ) . '</a>';
	}
	
	
	/**
	 * Add a page break button. This is more of a friendly addition instead of a core feature, 
	 * since it's lacking, might as well add it for convenience.
	 *
	 * @param $mceButtons array The existing TinyMCE buttons
	 * @return An array of TinyMCE buttons
	 * @see http://wpsites.net/wordpress-admin/how-to-add-next-page-links-in-posts-pages/3/
	 */
	public function addPageBreakButton( $mceButtons ) {
		// Don't add it if it already exists
		$pos = array_search( 'wp_page', $mceButtons, true );
		if ( $pos !== false ) {
			return;
		}
	
		// Add the page break button
		$pos = array_search( 'wp_more', $mceButtons, true );
		if ( $pos !== false ) {
			$tmpButtons = array_slice( $mceButtons, 0, $pos + 1 );
			$tmpButtons[] = 'wp_page';
			$mceButtons = array_merge( $tmpButtons, array_slice( $mceButtons, $pos + 1 ) );
		}
	
		return $mceButtons;
	}
	
}
new GambitPBSandwich();
