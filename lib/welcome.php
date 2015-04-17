<?php
/**
 * Welcome page that's shown when the plugin is activated
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * GambitPBSandwichWelcome Class
 *
 * Welcome page
 *
 * @since 0.10
 */
class GambitPBSandwichWelcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $header_text;
	public $header_desc;

	/**
	 * Get things started
	 *
	 * @since 0.10
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'adminMenus' ) );
		add_action( 'admin_head', array( $this, 'addStyles' ) );
		add_action( 'admin_init', array( $this, 'welcome' ) );

		$this->header_text = sprintf( __( 'Welcome to Page Builder Sandwich %s', 'pbsandwich' ), PBS_VERSION );
		$this->header_desc = sprintf( __( 'Thank you for updating! PB Sandwich %s offers a more stable experience.', 'pbsandwich' ), PBS_VERSION );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function adminMenus() {
		// About Page
		add_dashboard_page(
			__( 'Welcome to Page Builder Sandwich', 'pbsandwich' ),
			__( 'Welcome to Page Builder Sandwich', 'pbsandwich' ),
			'manage_options',
			'sandwich-about',
			array( $this, 'aboutScreen' )
		);

		// Changelog Page
		add_dashboard_page(
			__( 'Page Builder Sandwich Changelog', 'pbsandwich' ),
			__( 'Page Builder Sandwich Changelog', 'pbsandwich' ),
			'manage_options',
			'sandwich-changelog',
			array( $this, 'changelogScreen' )
		);

		// Getting Started Page
		add_dashboard_page(
			__( 'Getting started with Page Builder Sandwich', 'pbsandwich' ),
			__( 'Getting started with Page Builder Sandwich', 'pbsandwich' ),
			'manage_options',
			'sandwich-getting-started',
			array( $this, 'gettingStartedScreen' )
		);

		// Credits Page
		// add_dashboard_page(
		// 	__( 'The people who build Page Builder Sandwich', 'pbsandwich' ),
		// 	__( 'The people who build Page Builder Sandwich', 'pbsandwich' ),
		// 	$this->minimum_capability,
		// 	'sandwich-credits',
		// 	array( $this, 'creditsScreen' )
		// );
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function addStyles() {
		remove_submenu_page( 'index.php', 'sandwich-about' );
		remove_submenu_page( 'index.php', 'sandwich-changelog' );
		remove_submenu_page( 'index.php', 'sandwich-getting-started' );

		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.sandwich-badge {
		    height: 125px;
		    width: 125px;
			margin: 0 -5px;
			background: url('<?php echo PBS_URL . 'images/logo.png' ?>') no-repeat;
			background-size: contain;
		}

		.about-wrap .sandwich-badge {
			position: absolute;
			top: 0;
			right: 0;
		}
		
		.feature-section > img:first-of-type {
			width: 800px;
			max-width: 100%;
			height: auto;
			margin: 0 auto;
			display: block;
		}
		

		.about-wrap .feature-section {
			margin-top: 20px;
		}
		.about-overview {
			padding: 20px;
		}
		.about-overview iframe {
			display: block;
			margin: 0 auto;
		}

		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'sandwich-about';
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'sandwich-about' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sandwich-about' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'pbsandwich' ); ?>
			</a>
			<a class="nav-tab <?php echo $selected == 'sandwich-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sandwich-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'pbsandwich' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function aboutScreen() {
		?>
		<div class="wrap about-wrap">
			<h1><?php echo $this->header_text; ?></h1>
			<div class="about-text"><?php echo $this->header_desc; ?></div>
			<div class="sandwich-badge"></div>

			<?php $this->tabs(); ?>

			<div class="changelog">

				<!--div class="about-overview">
					<iframe width="640" height="360" src="//www.youtube.com/embed/" frameborder="0" allowfullscreen></iframe>
				</div-->
				<p class="about-description"><?php _e( 'Things just got more awesome', 'pbsandwich' );?></p>

				<div class="feature-section col two-col">

					<div class="col-1">
						<h3><?php _e( 'Add Features with Extensions', 'pbsandwich' );?></h3>
						<p><?php printf( __( 'We have included a ton of awesome features in Page Builder Sandwich. But we thought of nice-to-have features that we think will enhance your page building experience even better. From adding parallax and video backgrounds, to adding support for third-party eCommerce plugin shortcodes. View them from our website at %s', 'pbsandwich' ), '<a href="http://pbsandwi.ch/extensions" target="_blank">www.pbsandwi.ch</a>' ) ?></p>
					</div>

					<div class="col-2 last-feature">
						<img src="<?php echo PBS_URL . 'images/welcome-extensions.jpg'; ?>">
					</div>

				</div>
			
				<hr />

				<div class="feature-section col two-col">

					<div class="col-1">
						<h4><?php _e( 'Row & Column Settings', 'pbsandwich' );?></h4>
						<p><?php _e( 'New settings have been added to columns. Now you can edit margins, paddings, borders as well as add a background color or background image to them.', 'pbsandwich' ) ?></p>
					</div>

					<div class="col-2 last-feature">
						<img src="<?php echo PBS_URL . 'images/welcome-rows.jpg'; ?>">
					</div>

				</div>

				<hr />

				<div class="feature-section col two-col">

					<div class="col-1">
						<h4><?php _e( 'Third-Party Shortcodes', 'pbsandwich' );?></h4>
						<p><?php printf( __( 'If you have %s installed and activated, new shortcodes will become available', 'pbsandwich' ), '<strong>bbPress, MailChimp for WP, Ninja Forms, Contact Form 7</strong>' ) ?></p>
					</div>

					<div class="col-2 last-feature">
						<img src="<?php echo PBS_URL . 'images/welcome-shortcodes.jpg'; ?>">
					</div>

				</div>

				<hr />

				<div class="feature-section col two-col">

					<div class="col-1">
						<h4><?php _e( 'Easily Add Shortcodes', 'pbsandwich' );?></h4>
						<p><?php _e( 'We have added an "Add Post Element" button on the top of your visual editor for easy access. Click on it and choose your shortcode.', 'pbsandwich' ) ?></p>
					</div>

					<div class="col-2 last-feature">
						<img src="<?php echo PBS_URL . 'images/welcome-add-post-element.jpg'; ?>">
					</div>

				</div>

				<hr />

				<div class="feature-section col two-col">

					<div class="col-1">
						<h4><?php _e( 'Documentation', 'pbsandwich' ); ?></h4>
						<p>
							<a href="<?php echo esc_url( 'https://github.com/gambitph/Page-Builder-Sandwich/issues/new' ); ?>"><?php _e( 'Report a bug', 'pbsandwich' ); ?></a> &middot;
							<a href="<?php echo esc_url( 'https://github.com/gambitph/Page-Builder-Sandwich/wiki/' ); ?>"><?php _e( 'Documentation', 'pbsandwich' ); ?></a> &middot;
							<a href="<?php echo esc_url( 'https://github.com/gambitph/Page-Builder-Sandwich/wiki/' ); ?>"><?php _e( 'Contribute', 'pbsandwich' ); ?></a>
						</p>
					</div>

					<div class="col-2 last-feature">
					</div>

				</div>

			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sandwich-changelog' ), 'index.php' ) ) ); ?>"><?php _e( 'View the Full Changelog', 'pbsandwich' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Changelog Screen
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function changelogScreen() {
		?>
		<div class="wrap about-wrap">
			<h1><?php echo $this->header_text; ?></h1>
			<div class="about-text"><?php echo $this->header_desc; ?></div>
			<div class="sandwich-badge"></div>

			<?php $this->tabs(); ?>

			<div class="changelog">
				<h3><?php _e( 'Full Changelog', 'pbsandwich' );?></h3>

				<div class="feature-section">
					<?php echo $this->parseReadme(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Getting Started Screen
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function gettingStartedScreen() {
		?>
		<div class="wrap about-wrap">
			<h1><?php echo $this->header_text; ?></h1>
			<div class="about-text"><?php echo $this->header_desc; ?></div>
			<div class="sandwich-badge"></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Okay, so what is Sandwich and how is it different from the lot? To start..', 'pbsandwich' ); ?></p>

			<div class="changelog">

				<div class="feature-section">
					
					<h2 style="text-align: center"><?php _e( 'This is your brand new page builder', 'pbsandwich' );?></h2>
					<img src="<?php echo PBS_URL . 'images/guide-editor.jpg'; ?>">
					
					<p><?php _e( 'Looks like your normal visual editor huh? Sandwich adds awesome page builder capabilities to the WordPress visual editor. Create your content using the visual editor like how you normally would. Then we give you these powerful tools:', 'pbsandwich' ) ?></p>

				</div>

			</div>
			
			<hr />

			<div class="feature-section col two-col">

				<div class="col-1">
					<h3><?php _e( 'Visual Column Editing', 'pbsandwich' );?></h3>
					<p><?php _e( 'Forget those column shortcodes. You can easily create visual columns using the column button. Click on the button, choose the number of columns, then a new set of columns will be created.', 'pbsandwich' ) ?></p>
				</div>

				<div class="col-2 last-feature">
					<img src="<?php echo PBS_URL . 'images/guide-columns.jpg'; ?>">
				</div>

			</div>
			
			<hr />

			<div class="feature-section col two-col">

				<div class="col-1">
					<h3><?php _e( 'Add Features with Extensions', 'pbsandwich' );?></h3>
					<p><?php printf( __( 'We have included a ton of awesome features in Page Builder Sandwich. But we thought of nice-to-have features that we think will enhance your page building experience even better. From adding parallax and video backgrounds, to adding support for third-party eCommerce plugin shortcodes. View them from our website at %s', 'pbsandwich' ), '<a href="http://pbsandwi.ch/extensions" target="_blank">www.pbsandwi.ch</a>' ) ?></p>
				</div>

				<div class="col-2 last-feature">
					<img src="<?php echo PBS_URL . 'images/welcome-extensions.jpg'; ?>">
				</div>

			</div>
			
			<hr />

			<div class="feature-section col two-col">

				<div class="col-1">
					<h3><?php _e( 'Shortcodes (Page Elements)', 'pbsandwich' );?></h3>
					<p><?php _e( 'We have a growing list of shortcodes that you can use. We even support third party shortcodes from JetPack, bbPress, Ninja Forms, and plenty more. Get a live preview of what your shortcodes would look like while in the visual editor.', 'pbsandwich' ) ?></p>
				</div>

				<div class="col-2 last-feature">
					<img src="<?php echo PBS_URL . 'images/guide-shortcodes.jpg'; ?>">
				</div>

			</div>
			
			<hr />

			<div class="feature-section col two-col">

				<div class="col-1">
					<h3><?php _e( 'Drag and Drop', 'pbsandwich' );?></h3>
					<p><?php _e( 'Shortcodes that you insert are all drag and droppable. You can move them to different locations so that you can get the layout that you want.', 'pbsandwich' ) ?></p>
				</div>

				<div class="col-2 last-feature">
					<img src="<?php echo PBS_URL . 'images/guide-drag.jpg'; ?>">
				</div>

			</div>
			
			<hr />

			<div class="changelog">

				<div class="feature-section col three-col">

					<div class="col-1">
						<h3><?php _e( 'We Need Contributors', 'pbsandwich' );?></h3>
						<p><?php printf( __( 'Sandwich is quite new. And we have a long way to go to be a top notch page builder. If you have ideas for shortcodes or cool new features, head over to the %sPB Sandwich Github Repo%s', 'pbsandwich' ), '<a href="https://github.com/gambitph/Page-Builder-Sandwich">', '</a>' ) ?></p>
					</div>

					<div class="col-2">
						<h3><?php _e( 'Found a Bug?', 'pbsandwich' );?></h3>
						<p><?php printf( __( 'You can create a %snew issue%s and describe the bug you found.', 'pbsandwich' ), '<a href="https://github.com/gambitph/Page-Builder-Sandwich/issues/new">', '</a>' ); ?></p>
					</div>

					<div class="col-3 last-feature">
						<h3><?php _e( 'Need Support?', 'pbsandwich' );?></h3>
						<p><?php printf( __( 'We plan on adding some premium support when Sandwich reaches version 1.0. For now you can ask questions in Twitter %s@bfintal%s or our %sGithub repo%s', 'pbsandwich' ), '<a href="http://twitter.com/bfintal">', '</a>', '<a href="https://github.com/gambitph/Page-Builder-Sandwich">', '</a>' ); ?></p>
					</div>

				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render Credits Screen
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function creditsScreen() {
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to Ninja Forms %s', 'pbsandwich' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Ninja Forms %s is primed to make your experience managing submissions an enjoyable one!', 'pbsandwich' ), $display_version ); ?></div>
			<div class="sandwich-badge"></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Ninja Forms is created by a worldwide team of developers who aim to provide the #1 WordPress community form creation plugin.', 'pbsandwich' ); ?></p>

			<?php echo $this->contributors(); ?>
		</div>
		<?php
	}


	/**
	 * Parse the readme.txt file
	 *
	 * @since 0.10
	 * @return string $readme HTML formatted readme file
	 */
	public function parseReadme() {
		$file = file_exists( PBS_PATH . 'readme.txt' ) ? PBS_PATH . 'readme.txt' : null;

		if ( ! $file ) {
			$readme = '<p>' . __( 'No valid changelog was found.', 'pbsandwich' ) . '</p>';
		} else {
			$readme = file_get_contents( $file );
			$readme = nl2br( esc_html( $readme ) );

			$readme = end( explode( '== Changelog ==', $readme ) );

			$readme = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $readme );
			$readme = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme );
			$readme = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme );
			$readme = preg_replace( '/= (.*?) =/', '<h4>\\1</h4>', $readme );
			$readme = preg_replace( '/\[(.*?)\]\((.*?)\)/', '<a href="\\2">\\1</a>', $readme );
		}

		return $readme;
	}


	/**
	 * Render Contributors List
	 *
	 * @since 0.10
	 * @uses GambitPBSandwichWelcome::get_contributors()
	 * @return string $contributor_list HTML formatted list of all the contributors
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) )
			return '';

		$contributor_list = '<ul class="wp-people-group">';

		foreach ( $contributors as $contributor ) {
			$contributor_list .= '<li class="wp-person">';
			$contributor_list .= sprintf( '<a href="%s" title="%s">',
				esc_url( 'https://github.com/' . $contributor->login ),
				esc_html( sprintf( __( 'View %s', 'pbsandwich' ), $contributor->login ) )
			);
			$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= '</li>';
		}

		$contributor_list .= '</ul>';

		return $contributor_list;
	}

	/**
	 * Retreive list of contributors from GitHub.
	 *
	 * @access public
	 * @since 0.10
	 * @return array $contributors List of contributors
	 */
	public function get_contributors() {
		$contributors = get_transient( 'sandwich_contributors' );

		if ( false !== $contributors )
			return $contributors;

		$response = wp_remote_get( 'https://api.github.com/repos/gambitph/Page-Builder-Sandwich/contributors?&per_page=100', array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
			return array();

		$contributors = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_array( $contributors ) )
			return array();

		set_transient( 'sandwich_contributors', $contributors, 3600 );

		return $contributors;
	}

	/**
	 * Sends user to the Welcome page on first activation or update
	 *
	 * @access public
	 * @since 0.10
	 * @return void
	 */
	public function welcome() {
		// Bail if no activation redirect
		if ( ! get_transient( '_sandwich_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_sandwich_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		$upgrade = get_option( 'sandwich_version_upgrade_from' );

		if ( ! $upgrade ) { // First time install
			wp_safe_redirect( admin_url( 'index.php?page=sandwich-getting-started&is=new' ) );
			exit;
		} else { // Update
			wp_safe_redirect( admin_url( 'index.php?page=sandwich-about' ) );
			exit;
		}
	}
}
new GambitPBSandwichWelcome();


register_activation_hook( PBS_FILE, 'sandwich_activation' );
function sandwich_activation( $networkWide ) {
	// Add the transient to redirect
	if ( ! $networkWide ) {
		update_option( 'sandwich_version_upgrade_from', PBS_VERSION );
		set_transient( '_sandwich_activation_redirect', true, 30 );
	}
}