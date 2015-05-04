<?php

// TODO: Use a search-and-replace function to replace the <FRIENDLY_PLUGIN_NAME> with the plugin's proper name.
// Do the same for the function name and shortcode name (pbs_myshortcode), as well as the shortcode ui register name. (sandwich_myshortcode_shortcode)
// Delete this TODO message afterwards.

/**
 * Shortcode Template File for <FRIENDLY_PLUGIN_NAME>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode for <FRIENDLY_PLUGIN_NAME>
 */
add_action( 'init', 'sandwich_myshortcode', 11 );
function sandwich_myshortcode() {

	// TODO: Does your Shortcake require additional functions for rendering?
	// Most existing plugins using shortcodes can be rendered by shortcake itself.
	// However, plugins created from scratch will require the following add_shortcode module below.
	// Delete this TODO message along with the following function below if it is not needed, and 
	// DO NOT FORGET TO DELETE THE SHORTCODE INITIALIZATION BELOW THIS! 
	
	// Register shortcode for <FRIENDLY_PLUGIN_NAME>
	add_shortcode( 'pbs_myshortcode', 'sandwich_myshortcode_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Ensure plugin runs only in admin mode. Do not continue if that's not the case.
	if ( ! is_admin() ) {
		return;
	}
	
	// TODO: If creating a Shortcake for a particular plugin, find its class and use it as basis of activation.
	// This module must terminate the moment it determines that the dependency is not established.
	// Calling undefined classes and functions will result in errors, so this must be handled.
	// Delete this TODO module when done and the dependency check has been written and tested working.
	// Insert the dependency check below this line.
	
	// Register Shortcake UI for <FRIENDLY_PLUGIN_NAME>
	shortcode_ui_register_for_shortcode(
		'pbs_myshortcode',
		array(
			'label' => __( '<FRIENDLY_PLUGIN_NAME>', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress',
			'attrs' => array(
				array(
					'label' => __( 'Some Text', 'pbsandwich' ),
					'attr' => 'some_text',
					'type' => 'text',
				),
				array(
					'label' => __( 'Some Color', 'pbsandwich' ),
					'attr' => 'some_color',
					'type' => 'color',
					'value' => '#333333',
				),
			),
			'inner_content' => array(
				'label' => __( 'Content', 'pbsandwich' ),
				'value' => '',
				'type' => 'textarea',
			),
		)
	);
	
	// TODO: If the rendered shortcode in the editor NEEDS to be previewed in a logged out state (e.g. login forms)
	// uncomment this and add in your shortcode here.
	// sandwich_add_logged_out_shortcode( 'pbs_myshortcode' );
}

// TODO: Does your Shortcake require additional functions for rendering?
// Most existing plugins using shortcodes can be rendered by shortcake itself.
// However, plugins created from scratch will require the following module below.
// Delete this TODO message along with the following function below if it is not needed.
// DO NOT FORGET TO DELETE THE SHORTCODE INITIALIZATION ABOVE. 

/**
 * Render our shortcode
 */
function sandwich_myshortcode_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
		'some_text' => '',
		'some_color' => '',
	) );
	
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="myshortcode" style="background-color: <?php echo esc_attr( $attr['some_color'] ) ?>">
			<h3><?php echo esc_html( $attr['some_text'] ) ?></h3>
			<?php echo wpautop( do_shortcode( $content ) ) ?>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}