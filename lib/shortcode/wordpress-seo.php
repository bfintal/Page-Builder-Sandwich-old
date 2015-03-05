<?php

/**
 * Shortcode for WordPress SEO
 */


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_wordpress_seo', 11 );
function sandwich_wordpress_seo() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}

	// Check if WordPress SEO is active. Exit if not.
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		return;
	}
	
	// Register Shortcake UI for WordPress SEO Breadcrumbs
	shortcode_ui_register_for_shortcode(
		'wpseo_breadcrumb',
		array(
			'label' => __( 'WordPress SEO Breadcrumbs', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress',
		)
	);	
	
	// Register Shortcake UI for WordPress SEO Site Map
	shortcode_ui_register_for_shortcode(
		'wpseo_sitemap',
		array(
			'label' => __( 'WordPress SEO Site Map', 'pbsandwich' ),
			'listItemImage' => 'dashicons-wordpress',
		)
	);	
}
